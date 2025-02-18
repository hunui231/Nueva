<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProveedorGIC;


class ProveedorGICController extends Controller
{

    // Método para mostrar la vista con el gráfico
    public function index()
    {
        return view('proveedores-gic');
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
        $proveedor = ProveedorGIC::updateOrCreate(
            ['mes' => $request->mes], // Buscar por mes
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

    // Método para obtener los datos
    public function getData()
    {
        $proveedores = ProveedorGIC::all();
        return response()->json($proveedores);
    }
}
