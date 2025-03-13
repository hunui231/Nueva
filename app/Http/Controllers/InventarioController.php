<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;

class InventarioController extends Controller
{

    // Método para mostrar la vista con el gráfico
    public function index()
    {
        return view('inventario');
    }

    // Método para almacenar los datos del formulario
    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100', // Cambiado 'tendencia' a 'desempeno'
            'area_cumplimiento' => 'nullable|numeric|between:0,100', // Cambiado 'area' a 'area_cumplimiento'
        ]);

        // Actualizar o crear un registro
        $inventario = Inventario::updateOrCreate(
            ['mes' => $request->mes], // Buscar por mes
            [
                'desempeno' => $request->desempeno, // Cambiado 'tendencia' a 'desempeno'
                'area_cumplimiento' => $request->area_cumplimiento, // Cambiado 'area' a 'area_cumplimiento'
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $inventario,
        ]);
    }

    // Método para obtener los datos
    public function getData()
    {
        $inventarios = Inventario::all();
        return response()->json($inventarios);
    }

}
