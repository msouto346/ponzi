<div>
    <h2 class="text-green-400 text-center">Top Winners</h2>
    <table class="mt-10 w-full">
        <thead>
            <tr>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Name</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Price</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">% (24h)</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Market Cap</th>
            </tr>
        </thead>
        <tbody>

            @foreach($winners as $winner)
            <tr>
                <td class="border-t border-gray-200 text-center">{{$winner['name'] . ' (' . $winner['symbol'] . ')'}}
                </td>
                <td class="border-t border-gray-200 text-center">{{round($winner['quote']['USD']['price'], 2)}}</td>
                <td class="border-t border-gray-200 text-center text-green-400">{{round($winner['quote']['USD']['percent_change_24h'],
                    2) . '%'}}</td>
                <td class="border-t border-gray-200 text-center">
                    {{number_format($winner['quote']['USD']['market_cap'])}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="text-red-400 text-center mt-10">Bitconnect of the day</h2>
    <table class="mt-10 w-full">
        <thead>
            <tr>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Name</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Price</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">% (24h)</th>
                <th class="bg-gray-100 border-gray-200 px-6 py-4 text-gray-600">Market Cap</th>
            </tr>
        </thead>
        <tbody>

            @foreach($losers as $loser)
            <tr>
                <td class="border-t border-gray-200 text-center">{{$loser['name'] . ' (' . $loser['symbol'] . ')'}}
                </td>
                <td class="border-t border-gray-200 text-center">{{round($loser['quote']['USD']['price'], 2)}}</td>
                <td class="border-t border-gray-200 text-center text-red-400">{{round($loser['quote']['USD']['percent_change_24h'],
                    2) . '%'}}</td>
                <td class="border-t border-gray-200 text-center">
                    {{number_format($loser['quote']['USD']['market_cap'])}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
