<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    
    public function create(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'usuario' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'descripcion' => 'required|string',
        ]);

        // Crear un nuevo ticket
        $ticket = Ticket::create([
            'usuario' => $request->usuario,
            'area' => $request->area,
            'correo' => $request->correo,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json($ticket, 201); // Responder con el ticket creado
    }
}