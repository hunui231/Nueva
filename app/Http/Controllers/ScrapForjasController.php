<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScrapForjas;


class ScrapForjasController extends Controller
{

    // Método para mostrar la vista con el gráfico
    public function index()
    {
        return view('scrap-forjas');
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
        $scrap = ScrapForjas::updateOrCreate(
            ['mes' => $request->mes], // Buscar por mes
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $scrap,
        ]);
    }

    // Método para obtener los datos
    public function getData()
    {
        $scrap = ScrapForjas::all();
        return response()->json($scrap);
    }

}
