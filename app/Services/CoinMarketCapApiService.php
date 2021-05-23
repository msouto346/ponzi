<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class CoinMarketCapApiService
{
    private string $baseUrl;
    private array $headers;

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
}
