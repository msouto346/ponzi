<div>
    <div class="flex shadow">
        <input wire:model.debounce.1000ms="coin" class="w-full rounded p-2" type="search"
               placeholder="Search by the coin symbol...">
        <button class="bg-white w-auto flex justify-end items-center text-blue-500 p-2 hover:text-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </div>
    @if (strlen($coin) > 2 && is_array($coinResult))
        <div class="text-center pt-10 border-b border-black mb-4">
            <h3>{{$coinResult['name']}} ({{$coinResult['symbol']}})</h3>
            @if (! $alreadyAdded)
                <button wire:click="addToFavorites"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded mb-4">Add to
                    favorites
                </button>
            @endif
        </div>
    @elseif(strlen($coin) > 0)
        <div class="text-center pt-10 border-b border-black mb-4">
            <h3>There are no coins with that symbol in our database.</h3>
        </div>
    @else
        <h3 class="text-center pt-10 border-b border-black mb-4">Search for a coin above <br> to add it to your
            favorites
        </h3>
    @endif
    <h2 class="text-center">Favorites</h2>
    <button
        class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-blue-400 py-2 px-4 border border-blue-500 hover:border-transparent rounded mt-2"
        wire:click="refreshCoins">Refresh</button>
    <table class="mt-10 w-full">
        <thead>
        <tr>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Name</th>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Price</th>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">% (24h)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($favorites as $favorite)
            <tr>
                <td class="border-t border-gray-200 text-center">{{$favorite['symbol']}}
                <td class="border-t border-gray-200 text-center">
                {{round($favorite['quote']['USD']['price'], 2)}}
                <td class="border-t border-gray-200 text-center">
                    {{round($favorite['quote']['USD']['percent_change_24h'], 2)}}
                </td>
                <td valign="middle" align="center">
                    <svg wire:click="removeFromFavorites('{{$favorite['symbol']}}')" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{dump($favorites)}}
    @if(! $favorites->count())
        <h2 class="mt-8">You have no favorites yet</h2>
    @endif
</div>
