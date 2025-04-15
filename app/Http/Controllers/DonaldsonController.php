<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonaldsonProduccion;

class DonaldsonController extends Controller
{
    public function index()
    {
        return view('donaldson_produccion');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100',
            'area_cumplimiento' => 'nullable|numeric|between:0,100',
        ]);

        $cumplimiento = DonaldsonProduccion::updateOrCreate(
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

    public function getData()
    {
        $cumplimiento = DonaldsonProduccion::all();
        return response()->json($cumplimiento);
    }


}
