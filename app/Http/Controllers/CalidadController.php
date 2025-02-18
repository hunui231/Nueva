<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use App\Models\CalidadGrafico2;
use Illuminate\Http\Request;

class CalidadController extends Controller
{
 
    public function index()
    {
  
    return view('calidad.index'); 
    }

}




