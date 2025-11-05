<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estatus;
use App\Models\Tickets;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function create()
    {
        return view('tickets.create-ticket');
    }

    /**
     * Almacena un nuevo ticket de soporte en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'título' => ['required', 'string', 'max:255'],
            'descripción' => ['required', 'string'],
        ]);

        // 2. Obtener el ID del estatus inicial
        // Asume que el estatus inicial para un ticket nuevo es 'Abierto' o 'Pendiente'.
        // Necesitarás tener una entrada en tu tabla 'estatus' con ese nombre.
        try {
            $estatusAbierto = Estatus::where('nombre', 'Pendiente')->firstOrFail();
            $id_estatus_abierto = $estatusAbierto->id;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['estatus' => 'No se pudo encontrar el estatus inicial del ticket. Contacta a un administrador.']);
        }


        // 3. Creación del Ticket
        Tickets::create([
            'id_usuario' => Auth::id(), // El ID del usuario autenticado
            'título' => $request->título,
            'descripción' => $request->descripción,
            'id_estatus' => $id_estatus_abierto, // Asignar el estatus inicial
            'fecha_hora_reporte' => now(),
            'id_encargado' => null, // Dejar nulo, se asignará después
        ]);

        // 4. Redirección y mensaje de éxito
        return redirect()->route('crear.ticket')
                         ->with('status', '¡Ticket de soporte creado exitosamente! Un encargado lo revisará pronto.');
    }
}
