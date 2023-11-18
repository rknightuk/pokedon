@extends('layouts.app')

@section('title', env('APP_NAME'))

@section('content')


        <div class="connect">

        <h1>Enter your instance to connect to the Pokédon</h1>

        <form method="post" action="/auth/login">
            @csrf
            <input type="text" name="domain" placeholder="https://social.lol" value="https://social.lol">
            <input type="submit" value="Connect">
        </form>
    </div>

@endsection
