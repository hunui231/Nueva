<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    public function index()
    {
        return view('produccion.index');
    }
}
