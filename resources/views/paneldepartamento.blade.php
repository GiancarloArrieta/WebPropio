@extends('layout')

@section('tile','Panel de departamento')

@section('content')
    @if (Auth::user()->profile_photo_path)
        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
            style="width: 100px; height: 100px; border-radius: 70%;">
    @else
        <img src="{{ asset('storage/profiles/default-profile.png') }}"
            style="width: 100px; height: 100px; border-radius: 70%;">
    @endif
    <form method="POST" action="/logout">
    @csrf 
    
    <h1>Hola, {{ $user->name }}</h1>

    <a href="#" 
       onclick="event.preventDefault(); this.closest('form').submit();">
        Cerrar Sesión
    </a>
</form>
@endsection