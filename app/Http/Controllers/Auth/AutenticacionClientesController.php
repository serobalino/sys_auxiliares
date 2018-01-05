<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AutenticacionClientesController extends Controller{
    use AuthenticatesUsers;
    protected $redirectTo = '/cli';


    public function __construct(){
        $this->middleware('guest:cli',['except' => 'logout']);//cli y adm soporta
    }
    public function showLoginForm(){
        return view('login.index');
        //return csrf_token();
    }
    public function login(Request $request){
        if(Auth::guard('cli')->attempt(['ruc_cl'=>$request->ruc,'password'=>$request->psw],true)){
            return redirect()->intended(route('cli.inicio'));
        }else{
            return redirect()->back()->withInput($request->only('ruc_cl'));
        }
    }
}
