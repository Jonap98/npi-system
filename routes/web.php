<?php

use Illuminate\Support\Facades\Route;

use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MovimientosController;

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

// Route::get('/', function () {
//     $users = UsersModel::select('*')->get();

//     return view('welcome', array('users' => $users));
// });
// Route::group(['middleware' => ['auth']], function() {

    Route::get('movimientos', 'App\Http\Controllers\MovimientosController@create')->name('movimientos.create');
    Route::post('movimientos/store', 'App\Http\Controllers\MovimientosController@store')->name('store');
    
    // Partes
    Route::get('partes', 'App\Http\Controllers\PartesController@index')->name('partes');
    Route::get('partes/create', 'App\Http\Controllers\PartesController@create')->name('partes.create');
    Route::get('partes/{id}/edit', 'App\Http\Controllers\PartesController@edit')->name('partes.edit');
    Route::post('partes/{id}/update', 'App\Http\Controllers\PartesController@update')->name('partes.update');
    Route::post('partes/store', 'App\Http\Controllers\PartesController@store')->name('partes.store');
    Route::get('partes/{id}/delete', 'App\Http\Controllers\PartesController@destroy')->name('partes.delete');
    
    // Inventario
    Route::get('inventario/{id}/image', 'App\Http\Controllers\InventarioController@image')->name('inventario.image');
    
    
    // Requerimientos
    Route::get('requerimientos', 'App\Http\Controllers\RequerimientosController@index');
    Route::get('requerimientos/crear', 'App\Http\Controllers\RequerimientosController@create');
    
    
    
    
    // // Logout
    // Route::post('logout', 'App\Http\Controllers\Auth\LogoutController@store')->name('logout');
    
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
// });
Route::get('/', 'App\Http\Controllers\MovimientosController@index')->name('movimientos');

// Inventario
Route::get('inventario', 'App\Http\Controllers\InventarioController@index')->name('inventario');
//Excel
Route::get('/exportar', 'App\Http\Controllers\MovimientosController@export')->name('exportar');
Route::get('/exportar/inventario', 'App\Http\Controllers\InventarioController@export')->name('exportar.inventario');

// New Login
Route::post('new-login', 'App\Http\Controllers\PlainLoginController@login')->name('new-login');

Auth::routes();
