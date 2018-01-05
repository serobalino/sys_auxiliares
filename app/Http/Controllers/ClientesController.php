<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientesController extends Controller{
    public function __construct(){
        $this->middleware('auth:cli');
    }
    function index(){
        return (Auth::guard('cli')->user());
    }
}
