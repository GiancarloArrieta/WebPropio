@extends('layout')

@section('tile','Inicio de sesión')

@section('content')
    <h1>Iniciaste sesión como usuario</h1>
    @if (Auth::user()->profile_photo_path)
        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
            style="width: 100px; height: 100px; border-radius: 70%;">
    @else
        <img src="{{ asset('storage/profiles/default-profile.png') }}"
            style="width: 100px; height: 100px; border-radius: 70%;">
    @endif

    <form method="get" action="/perfil/editar">
        <button type="submit">Editar perfil</button>
    </form>

    <form method="POST" action="/usuario/ticket">
        @csrf 
        
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