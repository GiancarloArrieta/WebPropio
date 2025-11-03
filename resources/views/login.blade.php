@extends('layout')

@section('tile','Inicio de sesión')

@section('content')
    <h1>¡Bienvenido!</h1>
    <h2>Inicio de sesión</h2>
    <form method="post" action="/login">
        @csrf

        <label for="email">Correo:</label><br>
        <input type="email" id="email" name="email" placeholder="micorreo@dulcesricos.com" required/><br>
        <br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required/><br>
        <br>
        @error('email')
            <span style="color: red">{{ $message }}</span>
        @enderror
        <button type="submit">Iniciar sesión</button>
    </form>
@endsection