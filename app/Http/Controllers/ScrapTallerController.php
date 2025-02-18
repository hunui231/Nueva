<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScrapTaller;


class ScrapTallerController extends Controller
{

    // Método para mostrar la vista con el gráfico
    public function index()
    {
        return view('scrap-taller');
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
        $scrap = ScrapTaller::updateOrCreate(
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
        $scrap = ScrapTaller::all();
        return response()->json($scrap);
    }
}
