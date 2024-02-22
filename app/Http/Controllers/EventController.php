<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class EventController extends Controller
{
    protected $database;
    protected $ref_table;
    protected $ref_table_firestore;
    public function __construct(Database $database)
    {
        $this->ref_table_firestore = app('firebase.firestore')->database()->collection('Events');
        $this->ref_table = "Events";
        $this->database = $database;
    }

    public function create(){
        $postData = [
            "EventID" => "E2402005",
            "Name" => "Name5"
        ];
        $this->ref_table_firestore->newDocument()->set($postData);
        $postRef = $this->database->getReference($this->ref_table)->push($postData);
    }
}
