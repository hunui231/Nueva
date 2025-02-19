<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComprasGIC;


class ComprasGICController extends Controller
{
    public function index()
    {
        return view('compras-gic');
    }

    // Método para almacenar los datos del formulario
    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'required|numeric|between:0,100',
            'area_cumplimiento' => 'required|numeric|between:0,100',
        ]);

        // Actualizar o crear un registro
        $compras = ComprasGIC::updateOrCreate(
            ['mes' => $request->mes], // Buscar por mes
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $compras,
        ]);
    }

    // Método para obtener los datos
    public function getData()
    {
        $compras = ComprasGIC::all();
        return response()->json($compras);
    }
}
