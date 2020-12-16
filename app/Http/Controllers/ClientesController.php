<?php

namespace App\Http\Controllers;

use App\Clientes\Cliente;
use App\Clientes\Contrasena;
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
        return response(Cliente::with('contrasenas.label')->orderBy("apellidos_cl", 'asc')->orderBy("nombres_cl", 'asc')->get());
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'dni' => 'required|string|max:10|min:10|unique:clientes,dni_cl',
            'ruc' => 'required|string|max:13|min:13|unique:clientes,ruc_cl',
            'nombres' => 'required',
            'apellidos' => 'required',
            'razon' => 'required',
            'email' => 'nullable|email',
            'contrasenas' => 'array',
            'contrasenas.*.id_tc' => 'exists:tipo_clave,id_tc',
        ]);
        if ($validacion->fails()) {
            $texto = '';
            foreach ($validacion->errors()->all() as $errores)
                $texto .= $errores . PHP_EOL;
            return response(['val' => false, 'message' => $texto, 'datos' => $validacion->errors()->all()], 500);
        } else {
            $nuevo = new Cliente();
            $nuevo->nombres_cl = mb_strtoupper($request->nombres, 'UTF-8');
            $nuevo->apellidos_cl = mb_strtoupper($request->apellidos, 'UTF-8');
            $nuevo->dni_cl = $request->dni;
            $nuevo->ruc_cl = $request->ruc;
            $nuevo->email_cl = mb_strtolower($request->email, 'UTF-8');
            $nuevo->razon_cl = mb_strtoupper($request->razon, 'UTF-8');
            $nuevo->save();
            $this->validarClaves($nuevo,$request->contrasenas);
            return response(['val' => true, 'mensaje' => "Se guardó Correctamente $nuevo->apellidos_cl $nuevo->nombres_cl"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    private function validarClaves(Cliente $cliente,array $lista){
        foreach ($lista as $item){
            $clave = Contrasena::where('id_cl',$cliente->id_cl)
                ->where('id_tc',$item['id_tc'])
                ->where('estado_hc',true)
                ->where('contrasena_hc',$item['clave'])
                ->first();
            if(!$clave && $item['clave']){
                Contrasena::where('id_cl',$cliente->id_cl)
                    ->where('id_tc',$item['id_tc'])
                    ->update(['estado_hc' => false]);
                $clave = new Contrasena();
                $clave->id_cl = $cliente->id_cl;
                $clave->id_tc = $item['id_tc'];
                $clave->contrasena_hc = $item['clave'];
                $clave->save();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'codigo' => 'required|exists:clientes,id_cl',
            'nombres' => 'required',
            'apellidos' => 'required',
            'razon' => 'required',
            'email' => 'nullable|email',
            'contrasenas' => 'array',
            'contrasenas.*.id_tc' => 'exists:tipo_clave,id_tc',
        ]);
        if ($validacion->fails()) {
            $texto = '';
            foreach ($validacion->errors()->all() as $errores)
                $texto .= $errores . PHP_EOL;
            return response(['val' => false, 'message' => $texto, 'datos' => $validacion->errors()->all()], 500);
        } else {
            $nuevo = Cliente::find($request->codigo);
            $nuevo->nombres_cl = mb_strtoupper($request->nombres, 'UTF-8');
            $nuevo->apellidos_cl = mb_strtoupper($request->apellidos, 'UTF-8');
            $nuevo->razon_cl = mb_strtoupper($request->razon, 'UTF-8');
            $nuevo->email_cl = mb_strtolower($request->email, 'UTF-8');
            $nuevo->save();
            $this->validarClaves($nuevo,$request->contrasenas);
            return response(['val' => true, 'mensaje' => "Se actualizó Correctamente $nuevo->apellidos_cl $nuevo->nombres_cl"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
