<?php

namespace App\Http\Controllers;

use App\Models\ProveedorCI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProveedorCIController extends Controller
{
   // Método para mostrar la vista con el gráfico
    public function index()
    {
        $proveedores = ProveedorCI::all();
        return view('proveedores_ci', compact('proveedores'));
    }

    // Método para almacenar los datos del formulario
    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100',
            'area_cumplimiento' => 'nullable|numeric|between:0,100',
        ]);

        $proveedor = ProveedorCI::updateOrCreate(
            ['mes' => $request->mes],
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $proveedor,
        ]);
    }

    // Método para obtener los datos actualizados
    public function getData()
    {
        $proveedores = ProveedorCI::all();
        return response()->json($proveedores);
    }
}
