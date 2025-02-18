<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TallerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\RHController;
use App\Http\Controllers\ProveedorCIController;
use App\Http\Controllers\CobranzaController;
use App\Http\Controllers\CumplimientoCIController;
use App\Http\Controllers\ClientesNuevosController;
use App\Http\Controllers\PorcentajeVentasController;
use App\Http\Controllers\ScrapController;
use App\Http\Controllers\RendimientoController;
use App\Http\Controllers\CumplimientoProduccionController;
use App\Http\Controllers\RotacionControllerCI;
use App\Http\Controllers\PermanenciaControllerCI;
use App\Http\Controllers\RotacionControllerGIC;
use App\Http\Controllers\PermanenciaControllerGIC;
use App\Http\Controllers\AdministracionGICController;
use App\Http\Controllers\ScrapDonaldsonController;
use App\Http\Controllers\ScrapTallerController;
use App\Http\Controllers\ScrapForjasController;
use App\Http\Controllers\CumplimientoTallerController;
use App\Http\Controllers\ForjasProduccionController;
use App\Http\Controllers\ProveedorGICController;
use Illuminate\Support\Facades\Mail;
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

Route::get('forgot-password', [ForgotPasswordController::class, 'mostrarFormulario'])
    ->middleware('guest') 
    ->name('password.request');
Route::post('/enviar-correo', [App\Http\Controllers\ForgotPasswordController::class, 'enviarCorreo'])->name('enviar.correo');


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
 
    Route::get('/notificaciones', function () {
        return view('notificaciones');
    })->name('notificaciones');
    
    

    Route::get('/cuenta', [ActivityController::class, 'index'])->name('cuenta');


    Route::get('/administracion', [App\Http\Controllers\AdministracionController::class, 'index'])->name('administracion.index')->middleware('can:administracion.index');});

    Route::get('/Ventas', [App\Http\Controllers\VentasController::class, 'index'])
    ->name('Ventas.index')
    ->middleware('can:Ventas.index');

    Route::get('/produccion', [App\Http\Controllers\ProduccionController::class, 'index'])
    ->name('produccion.index')
    ->middleware('can:produccion.index');


    Route::get('/rh', [App\Http\Controllers\RHController::class, 'index'])
    ->name('rh.index')
    ->middleware('can:rh.index');

    Route::get('/administaciongic.index', [App\Http\Controllers\AdministracionGICController::class, 'index'])
    ->name('administraciongic.index')
    ->middleware('can:administraciongic.index');


    Route::post('/guardar-ci', [CobranzaController::class, 'guardarCI']);
  
    Route::post('/guardar-gic', [CobranzaController::class, 'guardarGIC']);

    Route::get('/obtener-ci', [CobranzaController::class, 'obtenerCI']);   
    
    Route::get('/obtener-gic', [CobranzaController::class, 'obtenerGIC']);


    Route::get('/proveedores-ci', [ProveedorCIController::class, 'index']);
    Route::post('/proveedores-ci/store', [ProveedorCIController::class, 'store']);
    Route::get('/proveedores-ci/get-data', [ProveedorCIController::class, 'getData']);
    
    Route::get('/cumplimiento-compras-ic', [CumplimientoCIController::class, 'index']);
    Route::post('/cumplimiento-compras-ic/store', [CumplimientoCIController::class, 'store']);
    Route::get('/cumplimiento-compras-ic/get-data', [CumplimientoCIController::class, 'getData']);

   Route::get('/clientes-nuevos', [ClientesNuevosController::class, 'index']);
   Route::post('/clientes-nuevos/store', [ClientesNuevosController::class, 'store']);
   Route::get('/clientes-nuevos/get-data', [ClientesNuevosController::class, 'getData']);
   
   Route::get('/ventas', [PorcentajeVentasController::class, 'index']);
   Route::post('/ventas/store', [PorcentajeVentasController::class, 'store']);
   Route::get('/ventas/get-data', [PorcentajeVentasController::class, 'getData']);

   Route::get('/scrap', [ScrapController::class, 'index']);
   Route::post('/scrap/store', [ScrapController::class, 'store']);
   Route::get('/scrap/get-data', [ScrapController::class, 'getData']);


   Route::get('/rendimiento', [RendimientoController::class, 'index']);
   Route::post('/rendimiento/store', [RendimientoController::class, 'store']);
   Route::get('/rendimiento/get-data', [RendimientoController::class, 'getData']);
   
   Route::post('/produccion/store', [CumplimientoProduccionController::class, 'store']);
   Route::get('/produccion/get-data', [CumplimientoProduccionController::class, 'getData']);

   
     Route::get('/rotacion', [RotacionControllerCI::class, 'index']);
     Route::post('/rotacion/store', [RotacionControllerCI::class, 'store']);
     Route::get('/rotacion/get-data', [RotacionControllerCI::class, 'getData']);

     Route::get('/permanencia', [PermanenciaControllerCI::class, 'index']);
     Route::post('/permanencia/store', [PermanenciaControllerCI::class, 'store']);
     Route::get('/permanencia/get-data', [PermanenciaControllerCI::class, 'getData']);

    
     Route::get('/rotacion-gic', [RotacionControllerGIC::class, 'index']);
     Route::post('/rotacion-gic/store', [RotacionControllerGIC::class, 'store']);
     Route::get('/rotacion-gic/get-data', [RotacionControllerGIC::class, 'getData']);

     Route::get('/permanencia-gic', [PermanenciaControllerGIC::class, 'index']);
     Route::post('/permanencia-gic/store', [PermanenciaControllerGIC::class, 'store']);
     Route::get('/permanencia-gic/get-data', [PermanenciaControllerGIC::class, 'getData']);

     Route::get('/scrap-donaldson', [ScrapDonaldsonController::class, 'index']);
     Route::post('/scrap-donaldson/store', [ScrapDonaldsonController::class, 'store']);
     Route::get('/scrap-donaldson/get-data', [ScrapDonaldsonController::class, 'getData']);

     Route::get('/scrap-taller', [ScrapTallerController::class, 'index']);
     Route::post('/scrap-taller/store', [ScrapTallerController::class, 'store']);
     Route::get('/scrap-taller/get-data', [ScrapTallerController::class, 'getData']);

     Route::get('/scrap-forjas', [ScrapForjasController::class, 'index']);
     Route::post('/scrap-forjas/store', [ScrapForjasController::class, 'store']);
     Route::get('/scrap-forjas/get-data', [ScrapForjasController::class, 'getData']);

     Route::get('/cumplimiento-taller', [CumplimientoTallerController::class, 'index']);
     Route::post('/cumplimiento-taller/store', [CumplimientoTallerController::class, 'store']);
     Route::get('/cumplimiento-taller/get-data', [CumplimientoTallerController::class, 'getData']);

     Route::get('/forjas-produccion', [ForjasProduccionController::class, 'index']);
     Route::post('/forjas-produccion/store', [ForjasProduccionController::class, 'store']);
     Route::get('/forjas-produccion/get-data', [ForjasProduccionController::class, 'getData']);
     

     Route::get('/proveedores-gic', [ProveedorGICController::class, 'index']);
     Route::post('/proveedores-gic/store', [ProveedorGICController::class, 'store']);
     Route::get('/proveedores-gic/get-data', [ProveedorGICController::class, 'getData']);