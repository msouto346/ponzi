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
        $response = $this->fetch('cryptocurrency/quotes/latest', ['symbol' => strtoupper($coin)]);
        if ($response['status']['error_code'] !== 0) {
            return $response['status']['error_message'];
        }

        return round($response['data'][strtoupper($coin)]['quote']['USD']['price'], 2);
    }

    public function getPercentage(string $coin, string $scale = '24h')
    {
        $response = $this->fetch('cryptocurrency/quotes/latest', ['symbol' => strtoupper($coin)]);
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
        $response = $this->fetch('cryptocurrency/quotes/latest', ['symbol' => strtoupper($coin)]);
        $responseType = 'symbol';
        if ($response['status']['error_code'] !== 0) {
            $response = $this->fetch('cryptocurrency/quotes/latest', ['slug' => strtolower($coin)]);
            $responseType = 'slug';
            if ($response['status']['error_code'] !== 0) {
                return $response['status']['error_message'];
            }
        }

        $infoArray = [
            'name',
            'symbol',
            'max_supply',
            'circulating_supply',
            'cmc_rank'
        ];


        $infoCollection = $responseType === 'symbol' ? collect($response['data'][strtoupper($coin)]) : collect(reset($response['data']));
        $responseCollection = $infoCollection->only($infoArray);
        $priceCollection = $infoCollection->only('quote')->flatMap(function ($item) {
            return $item;
        });

        return $responseCollection->merge($priceCollection)->toArray();
    }

    public function pumpOrDump($coin): array
    {
        $response = intval(round($this->getPercentage($coin)));
        switch (true) {
            case in_array($response, range(-1, -5)):
                return ['message' => 'It\'s just a lil bit of a dip', 'color' => 'red'];
            case in_array($response, range(-6, -9)):
                return ['message' => 'Well... I think this is good for bitcoin actually!', 'color' => 'red'];
            case in_array($response, range(-10, -19)):
                return ['message' => 'We just trying to shake the weak hands.', 'color' => 'red'];
            case $response < -20:
                return ['message' => 'Ponzi', 'color' => 'red'];
            case in_array($response, range(0, 5)):
                return ['message' => 'We doing ok.', 'color' => 'green'];
            case in_array($response, range(6, 9)):
                return ['message' => 'We pumping quite well.', 'color' => 'green'];
            case in_array($response, range(11, 19)):
                return ['message' => 'This quite the leg up!', 'color' => 'green'];
            case $response > 20:
                return ['message' => 'We pumping like a mother fucker!', 'color' => 'green'];
            default:
                return ['message' => 'I don\'t know whether we pumping or dumping...', 'color' => 'gray'];
        }
    }

    public function getTopCoins($number = 20)
    {
        $coins = $this->fetch('cryptocurrency/map', [
            'start' => 1,
            'limit' => $number,
            'sort' => 'cmc_rank'
        ]);

        if ($coins['status']['error_code'] !== 0) {
            return $coins['status']['error_message'];
        }

        $pluckedCoin = collect($coins['data'])->pluck('symbol');

        $multipleCoinsData = $this->fetch('cryptocurrency/quotes/latest', [
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

    public function getWinners($number = 5)
    {
        $coins = $this->fetch('cryptocurrency/listings/latest', [
            'percent_change_24h_min' => 10,
            'sort' => 'percent_change_24h',
            'sort_dir' => 'desc',
            'limit' => $number <= 20 ? $number : 20
        ]);

        if ($coins['status']['error_code'] !== 0) {
            return $coins['status']['error_message'];
        }

        return collect($coins['data']);
    }

    public function getLosers($number = 5)
    {
        $coins = $this->fetch('cryptocurrency/listings/latest', [
            'percent_change_24h_max' => -10,
            'sort' => 'percent_change_24h',
            'sort_dir' => 'asc',
            'limit' => $number <= 20 ? $number : 20
        ]);

        if ($coins['status']['error_code'] !== 0) {
            return $coins['status']['error_message'];
        }

        return collect($coins['data']);
    }
}
