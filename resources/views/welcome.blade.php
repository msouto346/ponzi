@extends('layouts.app')

@section('content')
<div class="grid grid-cols-3 gap-10 mx-4">
    @auth
    <livewire:show-top-coins>
        <div>
            <livewire:pump-or-dump>
                <livewire:show-coin-info>
        </div>
        <livewire:show-winners-and-losers>
</div>
@else
<h3>Login to see info about the coins</h3>
@endauth
@endsection
