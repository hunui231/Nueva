<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AdministracionGICController extends Controller
{
    public function index()
    {
        return view('administraciongic.index');
    }
}

