<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria5s;

class Auditoria5SController extends Controller
{
    public function index()
    {
        return view('auditoria-5s');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string',
            'desempeno' => 'nullable|numeric|between:0,100',
            'area_cumplimiento' => 'nullable|numeric|between:0,100',
        ]);

        $auditoria = Auditoria5s::updateOrCreate(
            ['mes' => $request->mes],
            [
                'desempeno' => $request->desempeno,
                'area_cumplimiento' => $request->area_cumplimiento,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $auditoria,
        ]);
    }

    public function getData()
    {
        $auditorias = Auditoria5s::all();
        return response()->json($auditorias);
    }
}