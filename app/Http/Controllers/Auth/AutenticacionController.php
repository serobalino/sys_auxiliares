<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AutenticacionController extends Controller{
    use AuthenticatesUsers;
    public function __construct(){
        $this->middleware(['guest','guest:adm'],['except' => 'logout']);//cli y adm soporta
    }
    public function showLoginForm(){
        return view('landing.login');
    }
    public function login(Request $request){
        $usuario    =   $request->usr;
        if(strpos($usuario,'@')){
            //es admin
            if(Auth::guard('adm')->attempt(['email_ad'=>$request->usr,'password'=>$request->psw],true))
                return (['val'=>true,'url'=>route('adm.inicio')]);
            else
                return (['val'=>false]);
        }else{
            //es cliente
            if(Auth::guard('cli')->attempt(['ruc_cl'=>$request->usr,'password'=>$request->psw],true))
                return (['val'=>true,'url'=>route('cli.inicio')]);
            else
                return (['val'=>false]);
        }
    }
    public function logout(Request $request){
        Auth::guard('adm')->logout();
        Auth::guard('cli')->logout();
        return redirect('/');
    }
}
