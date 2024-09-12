<?php

namespace App\Http\Controllers;

use App\Models\Logistica;
use Illuminate\Http\Request;

class LogisticaController extends Controller
{

    public function index()
    {
        // Obtener los datos de logística
        $logisticas = Logistica::all();

        return view('logistica.index', compact('logisticas'));
    }

}
