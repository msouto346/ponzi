<?php

namespace Tests\Feature;

use App\Http\Livewire\ShowCoinInfo;
use Livewire\Livewire;
use Tests\TestCase;

class ShowCoinInfoTest extends TestCase
{
    /** @test */
    public function it_shows_coin_info_correctly(): void
    {
        Livewire::test(ShowCoinInfo::class)
            ->assertDontSee('Bitcoin')
            ->set('coin', 'BTC')
            ->assertSee('Bitcoin');
    }

    /** @test */
    public function it_shows_message_if_no_coin_exists_for_that_symbol(): void
    {
        Livewire::test(ShowCoinInfo::class)
            ->set('coin', 'INVALIDCOINSYMBOL')
            ->assertSee('There are no coins with that symbol in our database.');
    }
}
