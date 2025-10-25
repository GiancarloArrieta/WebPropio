@extends('layout')

@section('tile','Inicio de sesión')

@section('content')
    <h1>Iniciaste sesión como departamento</h1>
    <form method="POST" action="/logout">
    @csrf 
    
    <a href="#" 
       onclick="event.preventDefault(); this.closest('form').submit();">
        Cerrar Sesión
    </a>
</form>
@endsection