<?php

namespace App\Http\Controllers;

use App\Models\Logistica;
use App\Models\Tlogistica2;
use App\Models\Tlogistica3;
use Illuminate\Http\Request;

class LogisticaController extends Controller
{

    public function index()
    {
        // Obtener los datos de logística
        $logisticas = Logistica::all();
        $tlogistica2 = Tlogistica2::first();
        $tlogistica3 = Tlogistica3::first();

        return view('logistica.index', compact('logisticas', 'tlogistica2', 'tlogistica3'));
    }

    public function update(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'dato' => 'required|numeric|min:0|max:100',
            'meta' => 'required|numeric|min:0|max:100',
        ]);

        // Actualizar o crear el registro en la base de datos
        Tlogistica2::updateOrCreate(
            ['id' => 1], // Suponiendo que solo hay un registro que se va a actualizar
            $request->only(['dato', 'meta'])
        );

        return redirect()->route('logistica.index')->with('success', 'Gráfico actualizado correctamente.');
    }
     
    
      public function updateDona2(Request $request)
      {
          $request->validate([
              'dato_dona2' => 'required|numeric|min:0|max:100',
              'meta_dona2' => 'required|numeric|min:0|max:100',
          ]);
  
          Tlogistica3::updateOrCreate(
              ['id' => 1], // Suponiendo que solo hay un registro que se va a actualizar
              [
                  'dato' => $request->input('dato_dona2'),
                  'meta' => $request->input('meta_dona2'),
              ]
          );
  
          return redirect()->route('logistica.index')->with('success', 'Segundo gráfico actualizado correctamente.');
      }

}
