@extends('layout')

@section('tile','Editar perfil')

@section('content')
    <h1>Editar Perfil de {{ $user->name }}</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="profile-forms">
        
        @include('profile.partials.update-profile-information-form')

        <hr>

        @include('profile.partials.update-profile-photo-form')
        
        <hr>
        
        @include('profile.partials.update-password-form')
        
    </div>
    <br><br>
    <form method="get" action="/">
        <button type="submit">Regresar</button>
    </form>
@endsection