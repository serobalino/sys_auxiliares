<?php

namespace App\Http\Controllers\Electronicas;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

//modelos


class SubirController extends Controller{
    public function __construct(){
        $this->middleware('auth:adm');
    }

    public function store(Request $request){
        $a  =   session()->get('cliente');
        $id =   Auth::guard('adm')->user()->id_ad;
        $path =storage_path("app/usuarios/$id/");
        $files = $request->file('file');
        $i=0;
        foreach($files as $file){
            $i++;
            $ext = $file->getClientOriginalExtension();
            $nom = $file->getClientOriginalName();
            $file->move($path,$nom);
        }
        return(['return'=>true,'url'=>URL::route('adm.cli.pro.ele',$a->ruc_cl),'token'=>csrf_token()]);
    }
}
