<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\test\TestMovimientosController;

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


Route::get('users', 'App\Http\Controllers\Auth\LoginController@index')->name('users');

Route::post('movimientos/test', [TestMovimientosController::class, 'testInventario'])->name('movimientos.test');
