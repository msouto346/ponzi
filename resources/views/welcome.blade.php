@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-3 mx-4">
    @auth
    <livewire:show-top-coins>
    <livewire:show-coin-info>
    <livewire:pump-or-dump>
    </div>
    @else
    <h3>Login to see info about the coins</h3>
    @endauth
@endsection
