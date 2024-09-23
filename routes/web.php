<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {    
   
    Route::get('/dashboard', function () { return view('layouts.welcome'); })->name('dashboard');
    
    /* USERS */
    Route::get('users', [UserController::class, 'index'])/*->middleware('can:users.index')*/->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/logistica', [App\Http\Controllers\LogisticaController::class, 'index'])->name('logistica.index')->middleware('can:logistica.index');
 
    Route::get('/calidad', [App\Http\Controllers\CalidadController::class, 'index'])->name('calidad.index')->middleware('can:calidad.index');
    Route::post('/calidad/update', [App\Http\Controllers\CalidadController::class, 'update'])->name('calidad.update');
    Route::post('/calidad/grafico2/update', [App\Http\Controllers\CalidadController::class, 'updateGrafico2'])->name('calidad.grafico2.update');

    Route::get('/cnc', [App\Http\Controllers\CncController::class, 'index'])->name('cnc.index')->middleware('can:cnc.index');

    Route::get('/tickets', function () {
        return view('tickets');
    })->name('tickets');

    Route::get('/cuenta', function () {
        return view('cuenta');
    })->name('cuenta');

    Route::get('/configuracion', function () {
        return view('configuracion');
    })->name('configuracion');
 


});