<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class CoinMarketCapApiService
{
    private $baseUrl;
    private $headers;

    public function __construct()
    {
        $this->baseUrl = 'https://pro-api.coinmarketcap.com/v1/';
        $this->headers = [
            'Accepts' => 'application/json',
            'X-CMC_PRO_API_KEY' => getenv('COIN_MARKET_CAP_API_KEY')
        ];
    }

    public function fetch(string $url, array $parameters = null): array
    {
        $response = Http::withHeaders($this->headers)
            ->get($this->baseUrl . $url, $parameters);

        return json_decode($response, true);
    }

    public function getPrice(string $coin)
    {
        $response = self::fetch('cryptocurrency/quotes/latest', ['symbol' => strtoupper($coin)]);
        if ($response['status']['error_code'] !== 0) {
            return $response['status']['error_message'];
        }

        return round($response['data'][strtoupper($coin)]['quote']['USD']['price'], 2);
    }

    public function getPercentage(string $coin, string $scale = '24h')
    {
        $response = self::fetch('cryptocurrency/quotes/latest', ['symbol' => strtoupper($coin)]);
        if ($response['status']['error_code'] !== 0) {
            return $response['status']['error_message'];
        }
        if (!isset($response['data'][strtoupper($coin)]['quote']['USD']['percent_change_' . $scale])) {
            return 'That timescale is not supported.';
        }

        return round($response['data'][strtoupper($coin)]['quote']['USD']['percent_change_' . $scale], 2);
    }

    public function getInfo(string $coin)
    {
        $response = self::fetch('cryptocurrency/quotes/latest', ['symbol' => strtoupper($coin)]);
        if ($response['status']['error_code'] !== 0) {
            return $response['status']['error_message'];
        }
        $infoArray = [
            'name',
            'symbol',
            'max_supply',
            'circulating_supply',
            'cmc_rank'
        ];

        $infoCollection = collect($response['data'][strtoupper($coin)]);
        $responseCollection = $infoCollection->only($infoArray);
        $priceCollection = $infoCollection->only('quote')->flatMap(function ($item) {
            return $item;
        });

        return $responseCollection->merge($priceCollection)->toArray();
    }

    public function pumpOrDump($coin): string
    {
        $response = intval(round(self::getPercentage($coin)));
        switch (true) {
            case in_array($response, range(-1, -5)):
                return 'It\'s just a lil bit of a dip';
            case in_array($response, range(-6, -9)):
                return 'Well... I think this is good for bitcoin actually!';
            case in_array($response, range(-10, -19)):
                return 'We just trying to shake the weak hands.';
            case $response < -20:
                return 'Ponzi';
            case in_array($response, range(0, 5)):
                return 'We doing ok.';
            case in_array($response, range(6, 9)):
                return 'We pumping quite well.';
            case in_array($response, range(11, 19)):
                return 'This quite the leg up!';
            case $response > 20:
                return 'We pumping like a mother fucker!';
            default:
                return 'I don\'t know whether we pumping or dumping...';
        }
    }

    public function getTopCoins($number = 20)
    {
        $coins = self::fetch('cryptocurrency/map', [
            'start' => 1,
            'limit' => $number,
            'sort' => 'cmc_rank'
        ]);

        if ($coins['status']['error_code'] !== 0) {
            return $coins['status']['error_message'];
        }

        $pluckedCoin = collect($coins['data'])->pluck('symbol');

        $multipleCoinsData = self::fetch('cryptocurrency/quotes/latest', [
            'symbol' => implode(',', $pluckedCoin->toArray()),
        ]);

        if ($multipleCoinsData['status']['error_code'] !== 0) {
            return $multipleCoinsData['status']['error_message'];
        }
        $topCoinsArray = collect($multipleCoinsData['data'])->sortBy('cmc_rank')->toArray();
        return array_map(function ($coin) {
            return [
                'name' => $coin['name'],
                'symbol' => $coin['symbol'],
                'quote' => $coin['quote']['USD']
            ];
        }, $topCoinsArray);
    }
}
