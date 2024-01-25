<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view("BackEnd.Home.login");
    }

    public function login()
    {
        return view("BackEnd.Home.login");
    }

    public function profile()
    {
        return view("BackEnd.Home.profile");
    }
}
