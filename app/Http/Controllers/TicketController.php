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

    /**
     * Muestra los detalles de un ticket específico.
     * @param \App\Models\Tickets $ticket El ticket inyectado por Route Model Binding.
     */
    public function show(Tickets $ticket)
    {
        // 1. Autorización: Aseguramos que solo el usuario creador pueda ver el ticket (puedes añadir más roles si es necesario).
        if ($ticket->id_usuario !== Auth::id() /* && !Auth::user()->is_admin */) {
            abort(403, 'Acceso no autorizado a este recurso.');
        }

        // 2. Cargar relaciones para mostrar la información completa
        $ticket->load(['estatus', 'usuario', 'encargado']);

        return view('detallesticket', compact('ticket'));
    }

    /**
     * Elimina un ticket solo si su estatus es "Pendiente" y el usuario es el creador.
     * @param \App\Models\Tickets $ticket El ticket inyectado por Route Model Binding.
     */
    public function destroy(Tickets $ticket)
    {
        // 1. Autorización: Solo el creador puede intentar eliminarlo.
        if ($ticket->id_usuario !== Auth::id()) {
            return back()->withErrors(['error' => 'No tienes permiso para eliminar este ticket.']);
        }

        // 2. Validación de Estatus: Cargar la relación para obtener el nombre del estatus.
        // Usamos load() para asegurarnos de que el estatus esté disponible.
        $ticket->load('estatus');
        
        if ($ticket->estatus->nombre !== 'Pendiente') {
            return back()->withErrors(['error' => 'Solo puedes eliminar tickets con estatus "Pendiente".']);
        }

        // 3. Eliminación
        $ticket->delete();

        // 4. Redirección
        return redirect()->route('usuario.panel')->with('status', 'Ticket eliminado exitosamente.');
    }
}
