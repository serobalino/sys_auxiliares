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

Route::get('/prueba',function(){
    $opts=array(
        'http'=>array(
            'user_agent'=>'PHPSoapClient'
        )
    );
    $context=stream_context_create($opts);
    $soapClientOptions=array(
        'stream_context'=>$context,
        'cache_wsdl'=>WSDL_CACHE_NONE
    );
    $url="https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl";
    //$url="https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl";
    $client=new SoapClient($url,$soapClientOptions);
    dd($client->__getTypes());
    $aux=$client->autorizacionComprobante(['claveAccesoComprobante'=>'1212201801179001691900121031030002294020121008317']);
    @$aux->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante_parseado=simplexml_load_string(@$aux->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
    //simplexml_load_string($string)
    //return response()->json($aux);

});

Auth::routes(['verify' => true]);

Route::middleware('verified')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('app')->group(function () {
        Route::resource("clientes","ClientesController");
    });
});
