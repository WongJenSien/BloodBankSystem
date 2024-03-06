<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
class EventAPIController extends Controller
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

    public function index(){
        return $this->database->getReference($this->ref_table)->getValue();
        // $reference = $this->ref_table_firestore->documents();
        // $data = collect($reference->rows());
        // $returnData = [];
        // foreach($data as $d){
        //     $returnData[] = $d->data();
        // }
        // return $returnData;
    }
}
