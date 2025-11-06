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

    <h2>Mis Tickets Reportados</h2>

    @if ($tickets->isEmpty())
        <p>Aún no has generado ningún ticket. ¡Haz clic en el botón para crear uno!</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estatus</th>
                    <th>Fecha de Reporte</th>
                    <th>Encargado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->título }}</td>
                        
                        {{-- Accedemos al nombre del estatus a través de la relación Eloquent --}}
                        <td>{{ $ticket->estatus->nombre ?? 'N/A' }}</td>
                        
                        {{-- Formateamos la fecha manualmente --}}
                        <td>{{ \Carbon\Carbon::parse($ticket->fecha_hora_reporte)->format('d/M/Y H:i') }}</td>
                        
                        {{-- Accedemos al nombre del encargado. El operador ?? maneja el caso de que el encargado sea nulo. --}}
                        <td>{{ $ticket->encargado->name ?? 'Sin asignar' }}</td>
                        
                        {{-- Columna de acciones (ejemplo) --}}
                        <td>
                            <a href="/tickets/{{ $ticket->id }}">Ver Detalles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <form method="POST" action="/logout">
        @csrf 
        
        <a href="#" 
        onclick="event.preventDefault(); this.closest('form').submit();">
            Cerrar Sesión
        </a>
    </form>
@endsection