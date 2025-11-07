@extends('layout')

@section('tile','Inicio de sesión')

@section('content')

    <h1>Generar nuevo ticket</h1>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                <h3 class="text-2xl font-bold mb-6 text-gray-700 border-b pb-2">Detalles del Problema</h3>

                {{-- Muestra los errores de validación si existen --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <strong class="font-bold">¡Oops!</strong>
                        <span class="block sm:inline">Hubo algunos problemas con tu solicitud.</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del ticket</label>
                        <input 
                            type="text" 
                            name="título" 
                            id="titulo" 
                            value="{{ old('título') }}"
                            required 
                            autofocus
                            placeholder="Ej. Problema con inicio de sesión o No puedo subir archivos"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('título') border-red-500 @enderror"
                        >
                        @error('título')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <br>
                    <div class="mb-6">
                        <label for="descripción" class="block text-sm font-medium text-gray-700 mb-1">Descripción del problema</label>
                        <textarea 
                            name="descripción" 
                            id="descripción" 
                            rows="6" 
                            required 
                            placeholder="Describe el problema paso a paso. Incluye mensajes de error y qué intentaste hacer."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripción') border-red-500 @enderror"
                        >{{ old('descripción') }}</textarea>
                        @error('descripción')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botón de Enviar --}}
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Enviar Ticket
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <form method="get" action="/">
        <button type="submit">Regresar</button>
    </form>

@endsection