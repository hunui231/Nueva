<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RotacionCI;


class RotacionControllerCI extends Controller
{
    
    // Método para mostrar la vista con el gráfico
    public function index()
    {
        return view('rotacion');
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
        $rotacion = RotacionCI::updateOrCreate(
            ['mes' => $request->mes], // Buscar por mes
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $rotacion,
        ]);
    }

    // Método para obtener los datos
    public function getData()
    {
        $rotacion = RotacionCI::all();
        return response()->json($rotacion);
    }

}
