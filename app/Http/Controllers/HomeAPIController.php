<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeAPIController extends Controller
{
    //
    public function index(){
        $value = "HALO";
        return $value;
    }
}
