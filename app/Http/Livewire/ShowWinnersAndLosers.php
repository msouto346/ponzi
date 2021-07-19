<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\CoinMarketCapApiService;

class ShowWinnersAndLosers extends Component
{
    public $winners;
    public $losers;

    protected $listeners = ['coinsRefreshed' => 'refreshComponent'];

    public function mount()
    {
        $this->winners = (new CoinMarketCapApiService())->getWinners(5);
        $this->losers = (new CoinMarketCapApiService())->getLosers(5);
    }

    public function render()
    {
        return view('livewire.show-winners-and-losers');
    }

    public function refreshComponent()
    {
        $this->winners = (new CoinMarketCapApiService())->getWinners(5);
        $this->losers = (new CoinMarketCapApiService())->getLosers(5);
    }
}
