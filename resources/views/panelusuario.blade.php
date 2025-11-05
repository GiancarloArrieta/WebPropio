@extends('layout')

@section('tile','Panel de usuario')

@section('content')
    @if (Auth::user()->profile_photo_path)
        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
            style="width: 100px; height: 100px; border-radius: 70%;">
    @else
        <img src="{{ asset('storage/profiles/default-profile.png') }}"
            style="width: 100px; height: 100px; border-radius: 70%;">
    @endif

    <h1>Hola, {{ $user->name }}</h1>

    <form method="get" action="/perfil/editar">
        <button type="submit">Editar perfil</button>
    </form>

    <form method="GET" action="/usuario/ticket">
        <button type="submit">Generar ticket</button>
    </form>

    <form method="POST" action="/logout">
        @csrf 
        
        <a href="#" 
        onclick="event.preventDefault(); this.closest('form').submit();">
            Cerrar Sesión
        </a>
    </form>
@endsection