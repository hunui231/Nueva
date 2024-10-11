<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TallerController;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;

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
    Route::post('/logistica/update', [App\Http\Controllers\LogisticaController::class, 'update'])->name('logistica.update');
    Route::post('/logistica/update-dona2', [App\Http\Controllers\LogisticaController::class, 'updateDona2'])->name('logistica.updateDona2');


    Route::get('/calidad', [App\Http\Controllers\CalidadController::class, 'index'])->name('calidad.index')->middleware('can:calidad.index');
    Route::post('/calidad/update', [App\Http\Controllers\CalidadController::class, 'update'])->name('calidad.update');
    Route::post('/calidad/grafico2/update', [App\Http\Controllers\CalidadController::class, 'updateGrafico2'])->name('calidad.grafico2.update');

    Route::get('/cnc', [App\Http\Controllers\CncController::class, 'index'])->name('cnc.index')->middleware('can:cnc.index');
    Route::post('/cnc/update', [App\Http\Controllers\CncController::class, 'update'])->name('cnc.update');
    Route::post('/cnc/updateGrafico2',[App\Http\Controllers\CncController::class, 'updateGrafico2'])->name('cnc.grafico2.update');

    Route::get('/tickets', function () {
        $tickets = Ticket::all(); // Obtener todos los tickets
        return view('tickets', compact('tickets')); // Pasar los tickets a la vista
    })->name('tickets');
  
    Route::post('/tickets', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');

    Route::get('/cuenta', function () {
        return view('cuenta');
    })->name('cuenta');

    Route::get('/configuracion', function () {
        return view('configuracion');
    })->name('configuracion');

    Route::get('/taller', [App\Http\Controllers\TallerController::class, 'index'])->name('taller.index');
 

});