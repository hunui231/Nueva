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
       
        $logisticas = Logistica::all();
        $tlogistica2 = Tlogistica2::first();
        $tlogistica3 = Tlogistica3::first();

        return view('logistica.index', compact('logisticas', 'tlogistica2', 'tlogistica3'));
    }

    public function update(Request $request)
    {
        
        $request->validate([
            'dato' => 'required|numeric|min:0|max:100',
            'meta' => 'required|numeric|min:0|max:100',
        ]);

      
        Tlogistica2::updateOrCreate(
            ['id' => 1], 
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
              ['id' => 1],
              [
                  'dato' => $request->input('dato_dona2'),
                  'meta' => $request->input('meta_dona2'),
              ]
          );
  
          return redirect()->route('logistica.index')->with('success', 'Segundo gráfico actualizado correctamente.');
      }
}
