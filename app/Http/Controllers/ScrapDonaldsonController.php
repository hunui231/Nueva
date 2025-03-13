<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScrapDonaldson;


class ScrapDonaldsonController extends Controller
{

    // Método para mostrar la vista con el gráfico
       // Método para mostrar la vista con el gráfico
       public function index()
       {
           return view('scrap-donaldson');
       }
   
       // Método para almacenar los datos del formulario
       public function store(Request $request)
       {
           $request->validate([
               'mes' => 'required|string',
               'desempeno' => 'nullable|numeric|between:0,100', // Cambiado de 'scrap' a 'desempeno'
               'area_cumplimiento' => 'nullable|numeric|between:0,100',
           ]);
   
           $scrap = ScrapDonaldson::updateOrCreate(
               ['mes' => $request->mes], 
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
           $scrap = ScrapDonaldson::all();
           return response()->json($scrap);
       }
}
