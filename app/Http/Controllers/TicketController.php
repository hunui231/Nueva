<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    
    public function create(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'descripcion' => 'required|string',
        ]);

        // Crear el ticket con estado predeterminado "Pendiente"
        $ticket = Ticket::create([
            'usuario' => $request->usuario,
            'area' => $request->area,
            'correo' => $request->correo,
            'descripcion' => $request->descripcion,
            'estado' => 'Pendiente', // Estado inicial
        ]);

        return response()->json($ticket, 201); // Retorna el ticket creado
    }

    // Cambiar el estado del ticket a "Listo"
    public function changeStatus(Ticket $ticket)
    {
        $ticket->estado = 'Listo';
        $ticket->save();

        return response()->json(['success' => true]);
    }

    // Eliminar un ticket
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(['success' => true]);
    }
}
