<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TallerController extends Controller
{
    
    public function index()
    {
        return view('taller.index');
    }
}
