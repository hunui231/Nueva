<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermanenciaGIC;


class PermanenciaControllerGIC extends Controller
{
  
    // Método para mostrar la vista con el gráfico
    public function index()
    {
        return view('permanencia-gic');
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
        $permanencia = PermanenciaGIC::updateOrCreate(
            ['mes' => $request->mes], // Buscar por mes
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $permanencia,
        ]);
    }

    // Método para obtener los datos
    public function getData()
    {
        $permanencia = PermanenciaGIC::all();
        return response()->json($permanencia);
    }    

}
