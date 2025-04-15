<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SatisfaccionCliente;

class SatisfaccionClienteController extends Controller
{
    public function index()
    {
        return view('satisfaccion-cliente');
    }

    // Método para almacenar los datos del formulario
    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100',
            'area_cumplimiento' => 'nullable|numeric|between:0,100',
        ]);

        // Actualizar o crear un registro
        $satisfaccion = SatisfaccionCliente::updateOrCreate(
            ['mes' => $request->mes], 
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $satisfaccion,
        ]);
    }

    // Método para obtener los datos
    public function getData()
    {
        $satisfacciones = SatisfaccionCliente::all();
        return response()->json($satisfacciones);
    }
}