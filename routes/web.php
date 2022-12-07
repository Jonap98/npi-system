<?php

use Illuminate\Support\Facades\Route;

use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\RegistroController;
use App\Http\Controllers\SolicitudRequerimientosController;
use App\Http\Controllers\RequerimientoManualController;
use App\Http\Controllers\RequerimientosController;
use App\Http\Controllers\BomsController;

Route::group(['middleware' => ['auth']], function() {

    // =======================================================
    // Users
    // =======================================================
    Route::post('register', [AuthController::class, 'register']);
    Route::get('registro', [RegistroController::class, 'index'])->name('registro');
    Route::post('registro/create', [RegistroController::class, 'store'])->name('registro.create');

    // =======================================================
    // Movimientos
    // =======================================================
    Route::get('movimientos', 'App\Http\Controllers\MovimientosController@create')->name('movimientos.create');
    Route::post('movimientos/store', 'App\Http\Controllers\MovimientosController@store')->name('store');
    
    // =======================================================
    // Partes
    // =======================================================
    Route::get('partes', 'App\Http\Controllers\PartesController@index')->name('partes');
    Route::get('partes/create', 'App\Http\Controllers\PartesController@create')->name('partes.create');
    Route::get('partes/{id}/edit', 'App\Http\Controllers\PartesController@edit')->name('partes.edit');
    Route::post('partes/{id}/update', 'App\Http\Controllers\PartesController@update')->name('partes.update');
    Route::post('partes/store', 'App\Http\Controllers\PartesController@store')->name('partes.store');
    Route::get('partes/{id}/delete', 'App\Http\Controllers\PartesController@destroy')->name('partes.delete');
    
    // =======================================================
    // Ubicaciones
    // =======================================================
    Route::get('ubicaciones', 'App\Http\Controllers\UbicacionesController@index')->name('ubicaciones');
    Route::post('ubicaciones/store', 'App\Http\Controllers\UbicacionesController@store')->name('ubicaciones.store');
    Route::get('ubicaciones/{id}/delete', 'App\Http\Controllers\UbicacionesController@destroy')->name('ubicaciones.delete');

    // =======================================================
    // Inventario
    // =======================================================
    Route::get('inventario/{id}/image', 'App\Http\Controllers\InventarioController@image')->name('inventario.image');
    
    // =======================================================
    // Requerimientos
    // =======================================================

    // Solicitudes
    Route::get('solicitudes/requerimientos', [SolicitudRequerimientosController::class, 'index'])->name('solicitud.requerimientos');
    Route::post('solicitudes/requerimientos', [SolicitudRequerimientosController::class, 'filter'])->name('solicitud.requerimientos.filter');
    Route::get('solicitudes/requerimientos/{folio}', [SolicitudRequerimientosController::class, 'details'])->name('solicitud.requerimientos.detalles');
    Route::post('solicitudes/requerimientos/update', [SolicitudRequerimientosController::class, 'update'])->name('solicitud.requerimientos.update');
    Route::post('solicitudes/requerimientos/export', [SolicitudRequerimientosController::class, 'exportPDF'])->name('solicitud.requerimientos.export');

    // Manual
    Route::get('requerimientos/manual', [RequerimientoManualController::class, 'index'])->name('requerimientos.manual');
    Route::get('requerimientos/{kit}/manual', [RequerimientoManualController::class, 'kit'])->name('requerimientos.kit.manual');
    Route::post('requerimientos/manual/solicitar', [RequerimientoManualController::class, 'solicitar'])->name('requerimientos.manual.solicitar');
    
    // Por modelo
    Route::get('requerimientos/modelo', [RequerimientosController::class, 'index'])->name('requerimientos.modelo');
    Route::get('requerimientos/{kit}/modelo', [RequerimientosController::class, 'kit'])->name('requerimientos.kit.modelo');
    Route::get('requerimientos/{kit}/detalles', [RequerimientosController::class, 'details'])->name('requerimientos.kit.detalles');
    Route::post('requerimientos/solicitar', [RequerimientosController::class, 'solicitar'])->name('requerimientos.solicitar');
    
    // Boms
    Route::get('boms', [BomsController::class, 'index'])->name('boms');
    Route::post('boms/import', [BomsController::class, 'import'])->name('boms.import');
    Route::post('boms/update', [BomsController::class, 'update'])->name('boms.update');
    Route::post('boms/edit', [BomsController::class, 'editName'])->name('boms.edit.name');
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    
    
});
    
Route::get('/', 'App\Http\Controllers\MovimientosController@index')->name('movimientos');

// Inventario
Route::get('inventario', 'App\Http\Controllers\InventarioController@index')->name('inventario');

//Excel
Route::get('/exportar', 'App\Http\Controllers\MovimientosController@export')->name('exportar');
Route::get('/exportar/inventario', 'App\Http\Controllers\InventarioController@export')->name('exportar.inventario');

// Auth
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
