<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalidadController extends Controller
{
 
    
    public function index()
    {
        // Lógica para obtener los datos de calidad
        return view('calidad.index');
    }

}
