<?php

namespace App\Http\Livewire;

use App\Services\CoinMarketCapApiService;
use Livewire\Component;

class ShowTopCoins extends Component
{

    public $coins = [];

    public function mount()
    {
        $this->coins = (new CoinMarketCapApiService())->getTopCoins();
    }

    public function render()
    {
        return view('livewire.show-top-coins');
    }

    public function refreshCoins()
    {
        $this->coins = (new CoinMarketCapApiService())->getTopCoins();
        $this->emit('coinsRefreshed', ['type' => 'success', 'message' => 'Coins refreshed']);
    }
}
