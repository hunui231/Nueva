<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use App\Models\CalidadGrafico2;
use Illuminate\Http\Request;

class CalidadController extends Controller
{
 
    public function index()
    {
    $calidad = Calidad::first();
    $calidadGrafico2 = CalidadGrafico2::first(); 

    return view('calidad.index', compact('calidad', 'calidadGrafico2')); 
    }

    public function update(Request $request)
    {
       
    $validatedData = $request->validate([
        'dato' => 'required|numeric',
        'meta' => 'required|numeric',
    ]);

    $calidad = Calidad::first(); 
    
    if (!$calidad) {
        $calidad = new Calidad();
    }

    $calidad->dato = $validatedData['dato'];
    $calidad->meta = $validatedData['meta'];
    $calidad->save();

    return redirect()->route('calidad.index')->with('success', 'Gráfico 1 actualizado correctamente');
    }

    public function updateGrafico2(Request $request)
    {
      
        $validatedData = $request->validate([
            'dato' => 'required|numeric',
            'meta' => 'required|numeric',
        ]);
    
        
        $calidadGrafico2 = CalidadGrafico2::first(); 
        
        if (!$calidadGrafico2) {
            $calidadGrafico2 = new CalidadGrafico2();
        }
    
        $calidadGrafico2->dato = $validatedData['dato'];
        $calidadGrafico2->meta = $validatedData['meta'];
        $calidadGrafico2->save();
    
        return redirect()->route('calidad.index')->with('success2', 'Gráfico 2 actualizado correctamente');
    }    
}




