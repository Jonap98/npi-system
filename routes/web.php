<?php

use Illuminate\Support\Facades\Route;

use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\PartesController;
use App\Http\Controllers\UbicacionesController;

use App\Http\Controllers\SolicitudRequerimientosController;
use App\Http\Controllers\RequerimientoManualController;
use App\Http\Controllers\RequerimientosController;
use App\Http\Controllers\BomsController;
use App\Http\Controllers\solicitudes\SolicitudDetallesController;

use App\Http\Controllers\requerimientos\ModelosController;
use App\Http\Controllers\requerimientos\KitsController;
use App\Http\Controllers\requerimientos\MakeController;

// Test
use App\Http\Controllers\test\TestMovimientosController;
use App\Http\Controllers\test\TestInventarioController;
use App\Http\Controllers\test\TestPartesController;

use App\Http\Controllers\InventarioExportController;

// Nuevo inventario
use App\Http\Controllers\inventario\InventarioController;


// Externals
// e-kanban
use App\Http\Controllers\externals\ekanban\RegisterController;


Route::group(['middleware' => ['auth']], function() {

    // =======================================================
    // Users
    // =======================================================
    Route::post('register', [AuthController::class, 'register']);
    Route::get('registro', [RegistroController::class, 'index'])->name('registro');
    Route::post('registro/create', [RegistroController::class, 'store'])->name('registro.create');

    Route::get('usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    Route::post('usuarios/{id}/update', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::get('usuarios/{id}/delete', [UsuariosController::class, 'destroy'])->name('usuarios.delete');

    // =======================================================
    // Movimientos
    // =======================================================
    Route::get('movimientos', [MovimientosController::class, 'create'])->name('movimientos.create');
    Route::post('movimientos/store', [MovimientosController::class, 'store'])->name('store');

    // =======================================================
    // Partes
    // =======================================================
    Route::get('partes', [PartesController::class, 'index'])->name('partes');
    Route::get('partes/create', [PartesController::class, 'create'])->name('partes.create');
    Route::post('partes/update', [PartesController::class, 'update'])->name('partes.update');
    Route::post('partes/store', [PartesController::class, 'store'])->name('partes.store');
    Route::post('partes/delete', [PartesController::class, 'destroy'])->name('partes.delete');

    // =======================================================
    // Ubicaciones
    // =======================================================
    Route::get('ubicaciones', [UbicacionesController::class, 'index'])->name('ubicaciones');
    Route::post('ubicaciones/store', [UbicacionesController::class, 'store'])->name('ubicaciones.store');
    Route::get('ubicaciones/{id}/delete', [UbicacionesController::class, 'destroy'])->name('ubicaciones.delete');

    // =======================================================
    // Inventario
    // =======================================================
    Route::get('inventario/{id}/image', [InventarioController::class, 'image'])->name('inventario.image');

    // =======================================================
    // Solicitudes
    // =======================================================

    // Solicitudes
    Route::get('solicitudes/requerimientos', [SolicitudRequerimientosController::class, 'index'])->name('solicitud.requerimientos');
    Route::post('solicitudes/requerimientos', [SolicitudRequerimientosController::class, 'filter'])->name('solicitud.requerimientos.filter');
    Route::get('solicitudes/requerimientos/{folio}', [SolicitudRequerimientosController::class, 'details'])->name('solicitud.requerimientos.detalles');
    Route::post('solicitudes/requerimientos/update', [SolicitudRequerimientosController::class, 'update'])->name('solicitud.requerimientos.update');
    Route::post('solicitudes/requerimientos/export', [SolicitudRequerimientosController::class, 'exportPDF'])->name('solicitud.requerimientos.export');
    Route::post('solicitudes/requerimientos/update-individual', [SolicitudRequerimientosController::class, 'prepararIndividual'])->name('solicitud.requerimientos.update-individual');
    Route::post('solicitudes/requerimientos/calcular-acumulado', [SolicitudRequerimientosController::class, 'calcularAcumulado'])->name('solicitud.requerimientos.calcular-acumulado');
    Route::post('solicitudes/requerimientos/preparar', [SolicitudRequerimientosController::class, 'preparar'])->name('solicitud.requerimientos.preparar');
    // Editar cantidad
    Route::post('solicitudes/requerimientos/edit', [SolicitudRequerimientosController::class, 'updateQty'])->name('solicitud.requerimientos.edit');
    Route::post('solicitudes/requerimientos/dynamic-edit', [SolicitudRequerimientosController::class, 'updateDynamicQty'])->name('solicitud.requerimientos.dynamic-edit');
    Route::post('solicitudes/requerimientos/update-status', [SolicitudRequerimientosController::class, 'updateStatus'])->name('solicitud.requerimientos.update-status');

    Route::post('solicitudes/requerimientos/delete', [SolicitudRequerimientosController::class, 'delete'])->name('solicitud.requerimientos.delete');

    Route::post('solicitud/requerimientos/delete-details', [SolicitudDetallesController::class, 'delete'])->name('solicitud.requerimientos.delete-details');


    // =======================================================
    // Requerimientos
    // =======================================================
    // Manual
    Route::get('requerimientos/manual', [RequerimientoManualController::class, 'index'])->name('requerimientos.manual');
    Route::get('requerimientos/{kit}/manual', [RequerimientoManualController::class, 'kit'])->name('requerimientos.kit.manual');
    Route::post('requerimientos/manual/solicitar', [RequerimientoManualController::class, 'solicitar'])->name('requerimientos.manual.solicitar');

    // Por modelo
    Route::get('requerimientos/{kit}/detalles', [RequerimientosController::class, 'details'])->name('requerimientos.kit.detalles');
    // Route::post('requerimientos/solicitar', [RequerimientosController::class, 'store'])->name('requerimientos.solicitar');


    // Modelos
    Route::get('requerimientos/modelo', [ModelosController::class, 'index'])->name('requerimientos.modelo');

    // Kits
    Route::get('requerimientos/{kit}/modelo', [KitsController::class, 'index'])->name('requerimientos.kit.modelo');
    Route::post('requerimientos/solicitarKits', [KitsController::class, 'store'])->name('requerimientos.kit.solicitarKits');
    Route::get('requerimientos/{id}/showKits', [KitsController::class, 'show'])->name('requerimientos.kit.showKits');
    Route::post('requerimientos/kits/generate-pdf', [KitsController::class, 'generatePDF'])->name('requerimientos.kits.generate-pdf');

    // Makes
    Route::get('requerimientos/{make}/make', [MakeController::class, 'index'])->name('requerimientos.kit.make');
    Route::post('requerimientos/solicitar', [MakeController::class, 'store'])->name('requerimientos.solicitar');

    Route::post('requerimientos/getInfo', [MakeController::class, 'getInfo'])->name('requerimientos.getInfo');

    Route::post('requerimientos/generate-pdf', [MakeController::class, 'generatePDF'])->name('requerimientos.generate-pdf');

    // Boms
    Route::get('boms', [BomsController::class, 'index'])->name('boms');
    Route::post('boms/import', [BomsController::class, 'import'])->name('boms.import');
    Route::post('boms/update', [BomsController::class, 'update'])->name('boms.update');
    Route::post('boms', [BomsController::class, 'edit'])->name('boms.edit');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('requerimientos/getkit/{kit}/', [RequerimientosController::class, 'getKit'])->name('requerimientos.getkit.detalles');





    // =======================================================
    // --------------------- TEST-----------------------------
    // =======================================================
    // Movimientos

    // Route::get('test', [TestMovimientosController::class, 'index'])->name('test.movimientos');
    Route::get('test', [TestMovimientosController::class, 'index'])->name('test.movimientos');

    Route::get('test/movimientos', [TestMovimientosController::class, 'create'])->name('test.movimientos.create');
    Route::post('test/movimientos/store', [TestMovimientosController::class, 'store'])->name('test.movimientos.store');

    // Inventario
    Route::get('test/inventario', [TestInventarioController::class, 'index'])->name('test.inventario');
    Route::get('test/inventario/{id}/image', [TestInventarioController::class, 'image'])->name('test.inventario.image');

    // Partes
    Route::get('test/partes', [TestPartesController::class, 'index'])->name('test.partes');
    Route::get('test/partes/create', [TestPartesController::class, 'create'])->name('test.partes.create');
    Route::get('test/partes/{id}/edit', [TestPartesController::class, 'edit'])->name('test.partes.edit');
    Route::post('test/partes/{id}/update', [TestPartesController::class, 'update'])->name('test.partes.update');
    Route::post('test/partes/store', [TestPartesController::class, 'store'])->name('test.partes.store');
    Route::get('test/partes/{id}/delete', [TestPartesController::class, 'destroy'])->name('test.partes.delete');

});

Route::get('/', [MovimientosController::class, 'index'])->name('movimientos');
// Route::get('/movimientos/filters/{cantidad?}', [MovimientosController::class, 'filters'])->name('movimientos.filters');
Route::post('/movimientos/filters', [MovimientosController::class, 'filters'])->name('movimientos.filters');

// Inventario
Route::get('/inventario/inventario', [InventarioController::class, 'index'])->name('inventario');

//Excel
Route::get('/exportar', [MovimientosController::class, 'export'])->name('exportar');
// NEW
Route::get('/exportar/inventario', [InventarioExportController::class, 'crearExcel'])->name('exportar.inventario');

// Auth
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');




// =========================================================================
// Externals
// =========================================================================
// e-kanban
Route::get('/e-kanban/register', [RegisterController::class, 'index'])->name('e-kanban.register');
Route::post('/e-kanban/register/register', [RegisterController::class, 'store'])->name('e-kanban.register.register');
