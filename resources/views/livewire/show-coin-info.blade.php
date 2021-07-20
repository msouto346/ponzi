<div>
    <div class="flex shadow">
        <input wire:model.debounce.1000ms="coin" class="w-full rounded p-2" type="search"
            placeholder="Search by the coin symbol...">
        <button class="bg-white w-auto flex justify-end items-center text-blue-500 p-2 hover:text-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
    @if (strlen($coin) > 2 && is_array($coinResult))
    <div class="text-center pt-10 border-b border-black mb-4">
        <h3>{{$coinResult['name']}} ({{$coinResult['symbol']}})</h3>
        <p>Market Cap: {{number_format($coinResult['USD']['market_cap'])}}</p>
        <p>Maximum Supply: {{number_format($coinResult['max_supply'])}}</p>
        <p>Circulating Supply: {{number_format($coinResult['circulating_supply'])}}</p>
        <p class="font-semibold">Rank: {{number_format($coinResult['cmc_rank'])}}</p>
        <h4>Price: {{round($coinResult['USD']['price'], 4)}}$ ({{round($coinResult['USD']['percent_change_24h'], 2)}}%)
        </h4>
    </div>
    @elseif(strlen($coin) > 0)
    <div class="text-center pt-10 border-b border-black mb-4">
        <h3>There are no coins with that symbol in our database.</h3>
    </div>
    @else
    <h3 class="text-center pt-10 border-b border-black mb-4">Search for a coin above <br> to view it's information...
    </h3>
    @endif
    <h2 class="text-center">Lastest added</h2>
    <table class="mt-10 w-full">
        <thead>
            <tr>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Name</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Price</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">% (24h)</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Market Cap</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Date Added</th>
            </tr>
        </thead>
        <tbody>
            @foreach($latest as $project)
            <tr>
                <td class="border-t border-gray-200 text-center">{{$project['name'] . ' (' . $project['symbol'] . ')'}}
                </td>
                <td class="border-t border-gray-200 text-center">{{round($project['quote']['USD']['price'], 2)}}</td>
                <td class="border-t border-gray-200 text-center">{{round($project['quote']['USD']['percent_change_24h'],
                    2) . '%'}}</td>
                <td class="border-t border-gray-200 text-center">
                    {{number_format($project['quote']['USD']['market_cap'])}}</td>
                <td class="border-t border-gray-200-text-center">
                    {{Carbon\Carbon::parse($project['date_added'])->format('d-m-Y')}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
