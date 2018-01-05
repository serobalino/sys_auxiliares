<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AutenticacionAdministradoresController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/adm';


    public function __construct(){
        $this->middleware('guest:adm',['except' => 'logout']);//cli y adm soporta

    }
    public function showLoginForm(){
        return view('login.index');
        //return csrf_token();
    }

    public function login(Request $request){
        if(Auth::guard('adm')->attempt(['email_ad'=>$request->ruc,'password'=>$request->psw],true)){
            return redirect()->intended(route('adm.inicio'));
        }else{
            return redirect()->back()->withInput($request->only('email_ad'));
        }
    }
}
