<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Cliente::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validacion =   Validator::make($request->all(), [
            'dni'       => 'required|string|size:13|unique:clientes,dni',
            'nombres'   => 'required',
            'apellidos' => 'required',
            'razon'     => 'required',
        ]);
        if($validacion->fails()){
            $texto  =   '';
            foreach ($validacion->errors()->all() as $errores)
                $texto.=$errores.PHP_EOL;
            return response(['val'=>false,'mensaje'=>$texto,'datos'=>$validacion->errors()->all()],500);
        }else{
            $nuevo                  =   new Cliente();
            $nuevo->nombres_cl      =   $request->nombres;
            $nuevo->apellidos_cl    =   $request->apellidos;
            $nuevo->dni_ci          =   $request->dni;
            $nuevo->razon_cl        =   $request->razon;
            $nuevo->save();
            return response(['val'=>true,'mensaje'=>"Se guardó Correctamente $nuevo->apellidos_cl $nuevo->nombres_cl"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
