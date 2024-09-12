<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CncController extends Controller
{

 
    public function index()
    {
        // Lógica para obtener los datos de cnc
        return view('cnc.index');
    }
}
