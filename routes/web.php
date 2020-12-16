<?php

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

Auth::routes(['verify' => true]);

Route::middleware('verified')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('app')->group(function () {
        Route::resource("clientes","ClientesController");
        Route::resource("comprobantes","GenerarAnexoController");

        Route::prefix('archivos')->group(function () {
            Route::post("reporte","SubirController@reporte");
            Route::post("comprobante","SubirController@comprobante");
        });
    });
    Route::prefix('ctlgs')->group(function () {
        Route::get("comprobantes", "CatalogosController@indexComprobantes");
        Route::get("claves", "CatalogosController@indexClave");
    });
});
