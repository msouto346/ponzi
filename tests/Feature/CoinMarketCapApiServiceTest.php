<?php

namespace Tests\Feature;

use App\Services\CoinMarketCapApiService;
use Tests\TestCase;

class CoinMarketCapApiServiceTest extends TestCase
{
    private CoinMarketCapApiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = (new CoinMarketCapApiService());
    }

    /** @test */
    public function it_can_fetch_a_response_from_coin_market_api_service(): void
    {
        $url = 'cryptocurrency/listings/latest';
        $request = $this->service->fetch($url);
        self::assertArrayHasKey('data', $request);
    }

    /** @test */
    public function it_returns_a_coin_price_if_symbol_is_valid(): void
    {
        $request = $this->service->getPrice('BTC');
        self::assertIsFloat($request);
    }

    /** @test */
    public function it_shows_error_message_if_symbol_is_invalid(): void
    {
        $request = $this->service->getPrice('Invalid.');
        self::assertIsString($request);
    }

    /** @test */
    public function it_shows_percentage_difference(): void
    {
        $request = $this->service->getPercentage('btc');
        self::assertIsFloat($request);
    }

    /** @test */
    public function it_returns_error_message_if_timescale_for_show_percentage_is_invalid(): void
    {
        $request = $this->service->getPercentage('btc', 'wrong timescale');
        self::assertStringContainsString('That timescale is not supported.', $request);
    }

    /** @test */
    public function it_returns_an_array_with_the_main_info_of_the_coin(): void
    {
        $infoArray = [
            'name',
            'symbol',
            'max_supply',
            'circulating_supply',
            'cmc_rank',
            'USD'
        ];
        $request = $this->service->getInfo('btc');
        self::assertIsArray($request);
        foreach ($infoArray as $key) {
            self::assertArrayHasKey($key, $request);
        }
    }
}
