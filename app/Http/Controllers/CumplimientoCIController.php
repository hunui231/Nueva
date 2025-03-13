<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CumplimientoCI;
use Illuminate\Support\Facades\DB;



class CumplimientoCIController extends Controller
{
    public function index()
    {
        $cumplimientos = CumplimientoCI::all();
        return view('cumplimiento_compras_ic', compact('cumplimientos'));
    }

    // Método para almacenar los datos del formulario
    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100',
            'area_cumplimiento' => 'nullable|numeric|between:0,100',
        ]);

        $cumplimiento = CumplimientoCI::updateOrCreate(
            ['mes' => $request->mes],
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $cumplimiento,
        ]);
    }

    // Método para obtener los datos actualizados
    public function getData()
    {
        $cumplimientos = CumplimientoCI::all();
        return response()->json($cumplimientos);
    }

}
