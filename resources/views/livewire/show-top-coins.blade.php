<div class="flex float-left">
    <table class="mt-20">
        <thead>
        <tr>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Name</th>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Price</th>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">% (24h)</th>
            <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Market Cap</th>
        </tr>
        </thead>
        <tbody>
        @foreach($coins as $coin)
            <tr>
                <td class="border-t border-gray-200 text-center">{{$coin['name'] . ' (' . $coin['symbol'] . ')'}}</td>
                <td class="border-t border-gray-200 text-center">{{round($coin['quote']['price'], 2)}}</td>
                <td class="border-t border-gray-200 text-center">{{round($coin['quote']['percent_change_24h'], 2) . '%'}}</td>
                <td class="border-t border-gray-200 text-center">{{number_format($coin['quote']['market_cap'])}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-blue-400 py-2 px-4 border border-blue-500 hover:border-transparent rounded absolute" wire:click="refreshCoins">Refresh</button>
</div>
