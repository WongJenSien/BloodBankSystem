<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ShipmentController extends Controller
{
    protected $database;
    protected $ref_table;
    protected $ref_table_firestore;
    public function __construct(Database $database)
    {
        $this->ref_table_firestore = app('firebase.firestore')->database()->collection('Inventories');
        $this->ref_table = "Shipment";
        $this->database = $database;
    }

    public function store(Request $request)
    {
        $quantity = array(
            'aPositive' => $request->aPositive,
            'aNegative' => $request->aNegative,

            'bPositive' => $request->bPositive,
            'bNegative' => $request->bNegative,

            'oPositive' => $request->oPositive,
            'oNegative' => $request->oNegative,

            'abPositive' => $request->abPositive,
            'abNegative' => $request->abNegative,
        );

        $shipmentID = $request->ship_id;
        $requestDate = $request->ship_today;
        $location = $request->location;
        $shipmentDate = $request->ship_date;
        $description = $request->description;

        $postData = [
            "Quantity" => $quantity,
            "ShipID" => $shipmentID,
            "RequestDate" => $requestDate,
            "location" => $location,
            "ShipDate" => $shipmentDate,
            "Description" => $description
        ];

        dd($postData);

        // $this->ref_table_firestore->newDocument()->set($postData);
        // $postRef = $this->database->getReference($this->ref_table)->push($postData);


    }
}
