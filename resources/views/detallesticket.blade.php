@extends('layout')

@section('tile', 'Detalles del Ticket #{{ $ticket->id }}')

@section('content')
    <div style="max-width: 800px; margin: 0 auto; padding: 20px; background-color: #f7f7f7; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        
        {{-- Mensajes de estado (éxito o error) --}}
        @if (session('status'))
            <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <h1 style="font-size: 2em; margin-bottom: 10px; color: #333;">Ticket de Soporte #{{ $ticket->id }}</h1>
        <h2 style="font-size: 1.5em; color: #555; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;">{{ $ticket->título }}</h2>

        <div style="background-color: white; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);">
            
            {{-- Sección de Metadatos --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #eee;">
                <div>
                    <strong style="color: #666;">Reportado por:</strong> 
                    <p>{{ $ticket->usuario->name ?? 'Usuario Desconocido' }}</p>
                </div>
                <div>
                    <strong style="color: #666;">Fecha de Reporte:</strong> 
                    <p>{{ \Carbon\Carbon::parse($ticket->fecha_hora_reporte)->format('d/M/Y H:i:s') }}</p>
                </div>
                <div>
                    <strong style="color: #666;">Estatus Actual:</strong> 
                    <p style="font-weight: bold; color: {{ $ticket->estatus->nombre === 'Pendiente' ? '#d9534f' : ($ticket->estatus->nombre === 'Completado' ? '#5cb85c' : '#f0ad4e') }};">
                        {{ $ticket->estatus->nombre ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <strong style="color: #666;">Encargado Asignado:</strong> 
                    <p>{{ $ticket->encargado->name ?? 'Sin Asignar' }}</p>
                </div>
            </div>

            {{-- Sección de Descripción --}}
            <div style="margin-bottom: 20px;">
                <strong style="color: #333; display: block; margin-bottom: 8px;">Descripción del Problema:</strong>
                <p style="white-space: pre-wrap; line-height: 1.6;">{{ $ticket->descripción }}</p>
            </div>

            {{-- Sección de Observaciones (Opcional) --}}
            @if ($ticket->observaciones)
            <div style="padding: 15px; background-color: #f9f9f9; border-left: 5px solid #5cb85c; margin-top: 20px;">
                <strong style="color: #333; display: block; margin-bottom: 5px;">Observaciones del Encargado:</strong>
                <p style="white-space: pre-wrap;">{{ $ticket->observaciones }}</p>
            </div>
            @endif

        </div>

        <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('usuario.panel') }}" style="text-decoration: none; padding: 10px 15px; background-color: #6c757d; color: white; border-radius: 4px; transition: background-color 0.3s;">
                ← Volver al Panel
            </a>

            {{-- Botón de Eliminar CONDICIONAL --}}
            {{-- 1. Validamos que el usuario logueado sea el creador del ticket. --}}
            @if ($ticket->id_usuario === Auth::id())
                {{-- 2. Validamos que el estatus sea "Pendiente". --}}
                @if ($ticket->estatus->nombre === 'Pendiente')
                    <form id="delete-form" method="POST" action="{{ route('tickets.destroy', $ticket) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        
                        <button type="button" 
                            onclick="mostrarConfirmacion()"
                            style="padding: 10px 15px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">
                            Eliminar Ticket
                        </button>
                    </form>
                @else
                    <button disabled style="padding: 10px 15px; background-color: #ccc; color: #666; border: none; border-radius: 4px; cursor: not-allowed;">
                        No se puede eliminar (Estatus: {{ $ticket->estatus->nombre }})
                    </button>
                @endif
            @endif
        </div>

    </div>

    {{-- Script para la ventana de confirmación personalizada --}}
    <script>
        function mostrarConfirmacion() {
            // Creamos un modal simple en el DOM
            const modalHtml = `
                <div id="custom-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); text-align: center;">
                        <h3 style="margin-top: 0; color: #dc3545;">Confirmar Eliminación</h3>
                        <p>¿Estás seguro de que deseas eliminar el ticket #{{ $ticket->id }} ({{ $ticket->título }})? Esta acción es irreversible.</p>
                        <div style="margin-top: 20px;">
                            <button onclick="document.getElementById('custom-modal').remove()" 
                                    style="padding: 8px 15px; background-color: #6c757d; color: white; border: none; border-radius: 4px; margin-right: 10px; cursor: pointer;">
                                Cancelar
                            </button>
                            <button onclick="document.getElementById('delete-form').submit()"
                                    style="padding: 8px 15px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                Sí, Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Inyectamos el modal en el cuerpo del documento
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
    </script>
@endsection