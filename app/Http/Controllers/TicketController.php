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

       
        $ticket = Ticket::create([
            'usuario' => $request->usuario,
            'area' => $request->area,
            'correo' => $request->correo,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json($ticket, 201); 
    }
}
