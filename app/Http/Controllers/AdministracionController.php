<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdministracionController extends Controller
{
    public function index()
    {
        return view('administacion.index');
    }  
  
}
