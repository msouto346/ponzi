<?php

namespace App\Http\Livewire;

use App\Services\CoinMarketCapApiService;
use Livewire\Component;

class ShowCoinInfo extends Component
{
    public $coin;
    public $coinResult;

    public function updatedCoin($newValue)
    {
        if(strlen($newValue) > 2) {
            $response = (new CoinMarketCapApiService())->getInfo($newValue);
            $this->coinResult = $response;
        }
    }

    public function render()
    {
        return view('livewire.show-coin-info');
    }
}
