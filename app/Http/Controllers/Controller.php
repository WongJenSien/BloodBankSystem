<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $database;
    protected $ref_table_shipment;
    protected $ref_table_inventories;
    protected $ref_table_hospital;
    protected $ref_table_event;
    protected $ref_table_user;
    protected $ref_table_appointment;
    public function __construct(Database $database)
    {
        $this->ref_table_shipment = "Shipment";
        $this->ref_table_inventories = "Inventories";
        $this->ref_table_event = 'Events';
        $this->ref_table_hospital = "Hospital";
        $this->ref_table_user = 'Users';
        $this->ref_table_appointment = "Appointment";
        $this->database = $database;
    }
    function checkLogin(){
        if (session()->has('user')) {
            return true;
        } else {
            return false;
        }
    }

    function uploadFile($file, $path)
    {
        $result = ['name' => '', 'path' => ''];

        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameSave = session('user.key') . '_' . time() . '.' . $extension;
        if ($file->storeAs($path, $fileNameSave)) {
            $result['name'] = $fileName;
            $result['path'] = $fileNameSave;
        }

        return $result;
    }

    function idGenerator($letter, $ref_table){
        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        $lastID = $this->database->getReference($ref_table)->orderByKey()->limitToLast(1)->getValue();
        if ($lastID != null) {
            $lastID = array_keys($lastID)[0];
        }

        //if no last record
        if ($lastID === null || substr($lastID, strlen($letter), 4) != substr($year, -2) . sprintf("%02s", $month)) {
            $newID = $letter . substr($year, -2) . sprintf("%02s", $month) . "001";
        } else {
            $newID = $lastID;
            $last = substr($newID, -3);
            $newNum = intval($last) + 1;

            $newID = $letter . substr($year, -2) . sprintf("%02s", $month) . sprintf("%03d", $newNum);
        }
        return $newID;
    }
}
