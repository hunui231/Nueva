<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CobranzaCI;
use App\Models\CobranzaGIC;
use Illuminate\Support\Facades\DB;


class CobranzaController extends Controller
{
   // Guardar o actualizar datos para CI
   public function guardarCI(Request $request)
   {
       $request->validate([
           'semana' => 'required|string',
           'en_tiempo' => 'required|numeric|min:0|max:100',
           'rango1' => 'required|numeric|min:0|max:100',
           'rango2' => 'required|numeric|min:0|max:100',
           'rango3' => 'required|numeric|min:0|max:100',
           'rango4' => 'required|numeric|min:0|max:100',
       ]);

       // Verificar si la suma de los rangos no supera el 100%
       $total = $request->en_tiempo + $request->rango1 + $request->rango2 + $request->rango3 + $request->rango4;
       if ($total > 100) {
           return response()->json(['error' => 'La suma de los rangos no puede superar el 100%.'], 400);
       }

       // Buscar si ya existe un registro para la semana
       $cobranzaCI = CobranzaCI::firstOrNew(['semana' => $request->semana]);

       // Actualizar los datos
       $cobranzaCI->en_tiempo = $request->en_tiempo;
       $cobranzaCI->rango1 = $request->rango1;
       $cobranzaCI->rango2 = $request->rango2;
       $cobranzaCI->rango3 = $request->rango3;
       $cobranzaCI->rango4 = $request->rango4;
       $cobranzaCI->save();

       return response()->json(['message' => 'Datos de CI guardados correctamente.']);
   }

   // Guardar o actualizar datos para GIC
   public function guardarGIC(Request $request)
   {
       $request->validate([
           'semana' => 'required|string',
           'en_tiempo' => 'required|numeric|min:0|max:100',
           'rango1' => 'required|numeric|min:0|max:100',
           'rango2' => 'required|numeric|min:0|max:100',
           'rango3' => 'required|numeric|min:0|max:100',
           'rango4' => 'required|numeric|min:0|max:100',
       ]);

       // Verificar si la suma de los rangos no supera el 100%
       $total = $request->en_tiempo + $request->rango1 + $request->rango2 + $request->rango3 + $request->rango4;
       if ($total > 100) {
           return response()->json(['error' => 'La suma de los rangos no puede superar el 100%.'], 400);
       }

       // Buscar si ya existe un registro para la semana
       $cobranzaGIC = CobranzaGIC::firstOrNew(['semana' => $request->semana]);

       // Actualizar los datos
       $cobranzaGIC->en_tiempo = $request->en_tiempo;
       $cobranzaGIC->rango1 = $request->rango1;
       $cobranzaGIC->rango2 = $request->rango2;
       $cobranzaGIC->rango3 = $request->rango3;
       $cobranzaGIC->rango4 = $request->rango4;
       $cobranzaGIC->save();

       return response()->json(['message' => 'Datos de GIC guardados correctamente.']);
   }
   
   // Obtener datos de CI
   public function obtenerCI() {
       $datos = DB::table('cobranza_ci')->get();  // Esto debería funcionar correctamente después de importar DB
       return response()->json($datos);
   }

   public function obtenerGIC() {
    $datos = DB::table('cobranza_gic')->get();  // Ajusta el nombre de la tabla si es necesario
    return response()->json($datos);
}


}