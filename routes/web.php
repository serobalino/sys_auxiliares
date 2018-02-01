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

///landing page
Route::get('/', 'LandingController@index')->name('index');
Route::post('/', 'LandingController@enviarMail');
Route::options('/', 'LandingController@verificar');

Route::post('facturas', 'ArchivosController@subirArchivos');
Route::get('facturas', 'ArchivosController@indexArchivos');


/*Login comun para todos*/
Route::get('/ingresar', 'Auth\AutenticacionController@showLoginForm')->name('login');
Route::post('/ingresar', 'Auth\AutenticacionController@login')->name('comun.login.submit');
Route::delete('/salir','Auth\AutenticacionController@logout')->name('logout');

/*Administradores login*/
Route::post('administradores','Auth\AutenticacionAdministradoresController@login')->name('adm.login.submit');
Route::get('administradores','Auth\AutenticacionAdministradoresController@showLoginForm')->name('adm.login');
//Route::get('/adm','AdministradoresController@index')->name('adm.inicio');

Route::group(['middleware' => 'auth:adm'], function () {

    Route::group(['middleware' => 'selecCliente'], function () {
        Route::get('/administrador/clientes/{ruc}', 'Clientes\ClientesController@ver')->name('adm.cli.ruc');
        Route::get('/administrador/clientes/{ruc}/gastos/electronicas', 'Clientes\ClientesController@electronicas')->name('adm.cli.sub.ele');
        Route::get('/administrador/clientes/{ruc}/gastos/electronicas/procesadas', 'Electronicas\ProcesarController@vista')->name('adm.cli.pro.ele');
        Route::post('/administrador/clientes/{ruc}/gastos/electronicas/procesadas', 'Electronicas\ProcesarController@procesar')->name('adm.cli.proajax.ele');

        Route::get('administrador/clientes/{ruc}/gastos/clasificar',function(){
            return('productos sin clasificar');
        });

        //perfil cliente
        Route::get('/administrador/clientes/{ruc}/perfil/contraseñas','PerfilCliente\ContrasenasController@index')->name('adm.cli.per.con');
        Route::get('/administrador/clientes/{ruc}/perfil/cuentasBancarias','PerfilCliente\BancariasController@index')->name('adm.cli.per.ban');
        Route::get('/administrador/clientes/{ruc}/perfil/documentos','PerfilCliente\DocumentosController@index')->name('adm.cli.per.doc');
        Route::post('/administrador/clientes/{ruc}/perfil/documentos','PerfilCliente\DocumentosController@store')->name('adm.cli.per.doc.post');
        Route::get('/administrador/clientes/{ruc}/perfil/','PerfilCliente\InformacionController@index')->name('adm.cli.per');

        //parsear facturas
        Route::group(['middleware' => 'parseados'], function () {
            Route::get('/administrador/clientes/{ruc}/perfil/rango', 'ComprobarComprobantesController@generar')->name('adm.cli.aux');
        });
        Route::post('/administrador/clientes/cliente/gastos/clasificar','ComprobarComprobantesController@guardar');
        Route::post('/administrador/clientes/cliente/gastos/clasificarProv','ComprobarComprobantesController@guardarProv');
        Route::get('/administrador/clientes/{ruc}/gastos/clasificar','ComprobarComprobantesController@clasificar')->name('adm.cli.sel');
        Route::post('/administrador/clientes/{ruc}/gastos/clasificar','ComprobarComprobantesController@resultado')->name('adm.cli.fecha');
        Route::get('/administrador/clientes/{ruc}/gastos/clasificar/excel','ComprobarComprobantesController@generar_excel')->name('adm.cli.aux.excel');
    });
    Route::get('/administrador/clientes', function () {
        return view('administrador.listaClientes');
    })->name('adm.inicio');

    //api admin
    Route::prefix('apiadm')->group(function () {
        Route::resource("/clientes",'Clientes\ClientesController',['only'=>['index','store','show']]);
        Route::resource("/gastos/subir",'Electronicas\SubirController',['only'=>['index','store','show']]);
        Route::get("/informacion",'ConfiguracionController@sessionApi')->name('session.adm');
    });
});


Route::get('/error',function() {
    abort(404) ;
});


/*Clientes login*/
Route::post('clientes','Auth\AutenticacionClientesController@login')->name('cli.login.submit');
Route::get('clientes','Auth\AutenticacionClientesController@showLoginForm')->name('cli.login');
Route::get('/cli','ClientesController@index')->name('cli.inicio');
