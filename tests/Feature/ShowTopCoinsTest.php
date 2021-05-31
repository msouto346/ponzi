<?php

namespace Tests\Feature;

use App\Http\Livewire\ShowTopCoins;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTopCoinsTest extends TestCase
{
    /** @test */
    public function main_page_contains_show_top_coins_component(): void
    {
        $this->get('/')
            ->assertSeeLivewire('show-top-coins');
    }

    /** @test */
    public function show_top_coins_emits_an_event_when_refresh_coins_method_gets_called(): void
    {
        Livewire::test(ShowTopCoins::class)
            ->call('refreshCoins')
            ->assertEmitted('coinsRefreshed');
    }
}
