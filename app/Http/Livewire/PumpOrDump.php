<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\CoinMarketCapApiService;

class PumpOrDump extends Component
{
    public $marketSentiment;

    protected $listeners = ['coinsRefreshed' => 'refreshComponent'];

    public function mount()
    {
        $this->marketSentiment = (new CoinMarketCapApiService())->pumpOrDump('btc');
    }

    public function render()
    {
        return view('livewire.pump-or-dump');
    }

    public function refreshComponent()
    {
        $this->marketSentiment = (new CoinMarketCapApiService())->pumpOrDump('btc');
    }
}
