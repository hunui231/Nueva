<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produccion;


class CumplimientoProduccionController extends Controller
{

   // Método para mostrar la vista con el gráfico
   public function index()
   {
       return view('produccion');
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
       $produccion = Produccion::updateOrCreate(
           ['mes' => $request->mes], // Buscar por mes
           [
               'desempeno' => $request->desempeno,
               'area_cumplimiento' => $request->area_cumplimiento,
           ]
       );

       return response()->json([
           'success' => true,
           'data' => $produccion,
       ]);
   }

   // Método para obtener los datos
   public function getData()
   {
       $produccion = Produccion::all();
       return response()->json($produccion);
   }
}
