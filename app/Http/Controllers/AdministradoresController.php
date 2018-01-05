<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministradoresController extends Controller{
    public function __construct(){
        $this->middleware('auth:adm');
    }

    function index(){
        return (Auth::guard('adm')->user());
    }
}
