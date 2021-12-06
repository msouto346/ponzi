@extends('layouts.app')

@section('content')
    @if(Auth::guest())
        <h3 class="text-center">Login to see info about the coins</h3>
    @endif
        @auth
            <div class="container text-center">
            <livewire:show-favorite-coins>
            </div>
    @endauth
@endsection
