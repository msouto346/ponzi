<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\ShowTopCoins;

class ShowTopCoinsTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function main_page_contains_show_top_coins_component(): void
    {
        $this->get('/home')
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
