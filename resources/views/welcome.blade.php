@extends('layouts.app')

@section('content')
    @if(Auth::guest())
        <h3 class="text-center">Login to see info about the coins</h3>
    @endif
    <div class="lg:grid lg:grid-cols-3 lg:gap-10 lg:mx-4">
        @auth
            <livewire:show-top-coins>
                <div>
                    <livewire:pump-or-dump>
                        <livewire:show-coin-info>
                </div>
                <livewire:show-winners-and-losers>
    </div>
    @endauth
@endsection
