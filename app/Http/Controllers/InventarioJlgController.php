<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventarioJlg;

class InventarioJlgController extends Controller
{
    public function index()
    {
        return view('inventario-jlg');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100',
            'area_cumplimiento' => 'nullable|numeric|between:0,100',
        ]);

        $inventario = InventarioJlg::updateOrCreate(
            ['mes' => $request->mes],
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $inventario,
        ]);
    }

    public function getData()
    {
        $inventarios = InventarioJlg::all();
        return response()->json($inventarios);
    }
}