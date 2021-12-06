<?php

namespace App\Http\Livewire;

use App\Services\CoinMarketCapApiService;
use Livewire\Component;

class ShowFavoriteCoins extends Component
{
    public $coin;
    public $coinResult;
    public $latest = [];
    public $favorites = [];
    public $alreadyAdded = false;

    public function mount()
    {
        $this->latest = (new CoinMarketCapApiService())->getLatest();
        $this->favorites = (new CoinMarketCapApiService())->favorites();
    }

    public function addToFavorites()
    {
        auth()->user()->favorites()->create([
            'symbol' => strtoupper($this->coin)
        ]);
        $this->favorites = (new CoinMarketCapApiService())->favorites();
        $this->alreadyAdded = true;
        info('favorites', [$this->favorites]);
    }

    public function removeFromFavorites(string $symbol)
    {
        auth()->user()->favorites()->where('symbol', strtoupper($symbol))->delete();
        unset($this->favorites[$symbol]);
    }

    public function updatedCoin($newValue)
    {
        if (strlen($newValue) > 2) {
            $response = (new CoinMarketCapApiService())->getInfo($newValue);
            $this->coinResult = $response;
            if (auth()->user()->favorites()->pluck('symbol')->contains(strtoupper($this->coin))) {
                $this->alreadyAdded = true;
            } else {
                $this->alreadyAdded = false;
            }
        }
    }

    public function refreshCoins()
    {
        $this->favorites = (new CoinMarketCapApiService())->favorites();
        $this->emit('coinsRefreshed', ['type' => 'success', 'message' => 'Coins refreshed']);
    }

    public function render()
    {
        return view('livewire.show-favorite-coins');
    }
}
