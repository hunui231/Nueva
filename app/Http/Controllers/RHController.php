<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RHController extends Controller
{

    public function index()
    {
        return view('rh.index');
    }
}
