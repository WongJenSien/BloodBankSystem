<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventAPIController extends Controller
{
    //
    public function index(){
        return $this->database->getReference($this->ref_table_event)->getValue();
    }
}
