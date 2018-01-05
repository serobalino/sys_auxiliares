<?php

namespace App\Http\Controllers\Clientes;


use Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//modelos
use App\Cliente;
use App\doc_cliente;

class ClientesController extends Controller
{
    public function index(){
        session()->forget('cliente');
        $resultado  =   Cliente::all();
        return $resultado;
    }
    public  function store(Request $request){
        $validator = Validator::make($request->all(), [
            'ruc'       => 'required|max:13|min:13',
            'apellidos' => 'required',
            'nombres'   => 'required',
            'rsocial'   => 'required',
        ]);
        if ($validator->fails()) {
            return (['return'=>false,'mensaje'=>'Falta un campo']);
        }else{
            $comprobar = Cliente::where('ruc_cl',$request->ruc)->get();
            if(count($comprobar)<=0){
                $cliente    =   new Cliente();//guarda el cliente
                $cliente->ruc_cl        =   $request->ruc;
                $cliente->nombres_cl    =   $request->nombres;
                $cliente->apellidos_cl  =   $request->apellidos;
                $cliente->razon_cl      =   $request->rsocial;
                $cliente->id_es         =   1;
                $cliente->save();

                $ruc_cl             =   new doc_cliente();//guarda su ruc en el registro de documentos
                $ruc_cl->codigo_td  =   4;
                $ruc_cl->id_cl      =   $cliente->id_cl;
                $ruc_cl->num_dc     =   $cliente->ruc_cl;
                $ruc_cl->save();

                return (['return'=>true,'mensaje'=>'Se guardo con exito '.$request->rsocial]);
            }else{
                return (['return'=>false,'mensaje'=>'Ya existe un cliente con ese ruc']);
            }
        }
    }
    public function show($id){
        if ($id<0) {
            return (['return'=>false,'mensaje'=>'Id icorrecta']);
        }else{
            $comprobar = Cliente::find($id);
            if(count($comprobar)>0){
                session(['cliente' => $comprobar]);
                return (['return'=>true,'ruta'=>URL::route('adm.cli.ruc',$comprobar->ruc_cl)]);
            }else{
                return (['return'=>false,'mensaje'=>'No existe ese cliente']);
            }
        }
    }
    public function ver($ruc){
        $a  =   session()->get('cliente');
        return view('basico.perfilCliente',$a);
    }
    public function electronicas($ruc){
        $a  =   session()->get('cliente');
        $id =   Auth::guard('adm')->user()->id_ad;
        Storage::deleteDirectory("/usuarios/$id/");//elimina las facturas subidas
        return view('basico.electronicas',$a);
    }
}
