<?php

namespace Tests\Feature;

use App\Services\CoinMarketCapApiService;
use Tests\TestCase;

class CoinMarketCapApiServiceTest extends TestCase
{
    private $service;

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

    /** @test */
    public function it_returns_a_string_saying_if_the_market_is_pumping_or_dumping(): void
    {
        $percentage = $this->service->getPercentage('btc');
        $pumpOrDump = $this->service->pumpOrDump('btc');
        $integer = intval(round($percentage));

        switch (true) {
            case in_array($integer, range(-1, -5)):
                $string = 'It\'s just a lil bit of a dip';
                break;
            case in_array($integer, range(-6, -9)):
                $string = 'Well... I think this is good for bitcoin actually!';
                break;
            case in_array($integer, range(-10, -19)):
                $string = 'We just trying to shake the weak hands.';
                break;
            case $integer < -20:
                $string = 'Ponzi';
                break;
            case in_array($integer, range(0, 5)):
                $string = 'We doing ok.';
                break;
            case in_array($integer, range(6, 9)):
                $string = 'We pumping quite well.';
                break;
            case in_array($integer, range(11, 19)):
                $string = 'This quite the leg up!';
                break;
            case $integer > 20:
                $string = 'We pumping like a mother fucker!';
                break;
            default:
                $string = 'I don\'t know whether we pumping or dumping...';
        }
        $this->assertStringContainsString($string, $pumpOrDump);
    }

    /** @test */
    public function it_returns_an_array_with_the_main_coins(): void
    {
        $request = $this->service->getTopCoins();
        $this->assertCount(20, $request);
    }
}
