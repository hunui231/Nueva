<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('created_at', 'desc')->take(5)->get();
        return view('cuenta', compact('activities'));
    }
}
