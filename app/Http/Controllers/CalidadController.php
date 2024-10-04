<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use App\Models\CalidadGrafico2;
use Illuminate\Http\Request;

class CalidadController extends Controller
{
 
    public function index()
    {
    $calidad = Calidad::first(); // Obtener los datos existentes del primer gráfico
    $calidadGrafico2 = CalidadGrafico2::first(); // Obtener los datos del segundo gráfico

    return view('calidad.index', compact('calidad', 'calidadGrafico2')); // Pasar ambas variables a la vista
    }

    public function update(Request $request)
    {
       
    // Validar los datos del formulario
    $validatedData = $request->validate([
        'dato' => 'required|numeric',
        'meta' => 'required|numeric',
    ]);

    // Guardar o actualizar los datos en la base de datos
    $calidad = Calidad::first(); // Obtén el primer registro de la tabla "calidad"
    
    if (!$calidad) {
        $calidad = new Calidad(); // Si no existe, crea un nuevo registro
    }

    $calidad->dato = $validatedData['dato'];
    $calidad->meta = $validatedData['meta'];
    $calidad->save();

    return redirect()->route('calidad.index')->with('success', 'Gráfico 1 actualizado correctamente');
    }

    public function updateGrafico2(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'dato' => 'required|numeric',
            'meta' => 'required|numeric',
        ]);
    
        // Guardar o actualizar los datos en la base de datos
        $calidadGrafico2 = CalidadGrafico2::first(); // Obtén el primer registro de la tabla "calidad_grafico2"
        
        if (!$calidadGrafico2) {
            $calidadGrafico2 = new CalidadGrafico2(); // Si no existe, crea un nuevo registro
        }
    
        $calidadGrafico2->dato = $validatedData['dato'];
        $calidadGrafico2->meta = $validatedData['meta'];
        $calidadGrafico2->save();
    
        return redirect()->route('calidad.index')->with('success2', 'Gráfico 2 actualizado correctamente');
    }

    
    

}




