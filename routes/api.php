<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('movimientos', 'App\Http\Controllers\MovimientosController@index');

Route::post('movimientos/create', 'App\Http\Controllers\MovimientosController@store');

Route::get('/movimientos/test', 'App\Http\Controllers\MovimientosController@indextestGroupBy')->name('movimientos.test');

Route::get('inventario/test', 'App\Http\Controllers\InventarioController@test')->name('inventario.test');
Route::get('inventario/test2', 'App\Http\Controllers\InventarioController@test2')->name('inventario.test2');
Route::get('inventario/test3', 'App\Http\Controllers\InventarioController@test3')->name('inventario.test3');

Route::get('users', 'App\Http\Controllers\Auth\LoginController@index')->name('users');
