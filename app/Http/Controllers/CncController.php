<?php

namespace App\Http\Controllers;

use App\Models\Cnc;
use App\Models\CncGrafico2;
use Illuminate\Http\Request;


class CncController extends Controller
{

 
    public function index()
    {

        $cnc = Cnc::first(); // Obtener los datos existentes del primer gráfico
        $cncGrafico2 = CncGrafico2::first(); // Obtener los datos del segundo gráfico

        return view('cnc.index', compact('cnc', 'cncGrafico2'));

    }


    public function update(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'dato' => 'required|numeric',
            'meta' => 'required|numeric',
        ]);

        // Guardar o actualizar los datos en la base de datos
        $cnc = Cnc::first(); // Obtén el primer registro de la tabla "cnc"
        
        if (!$cnc) {
            $cnc = new Cnc(); // Si no existe, crea un nuevo registro
        }

        $cnc->dato = $validatedData['dato'];
        $cnc->meta = $validatedData['meta'];
        $cnc->save();

        return redirect()->route('cnc.index')->with('success', 'Gráfico 1 actualizado correctamente');
    }

    public function updateGrafico2(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'dato' => 'required|numeric',
            'meta' => 'required|numeric',
        ]);

        // Guardar o actualizar los datos en la base de datos
        $cncGrafico2 = CncGrafico2::first(); // Obtén el primer registro de la tabla "cnc_grafico2"
        
        if (!$cncGrafico2) {
            $cncGrafico2 = new CncGrafico2(); // Si no existe, crea un nuevo registro
        }

        $cncGrafico2->dato = $validatedData['dato'];
        $cncGrafico2->meta = $validatedData['meta'];
        $cncGrafico2->save();

        return redirect()->route('cnc.index')->with('success2', 'Gráfico 2 actualizado correctamente');
    }

}

