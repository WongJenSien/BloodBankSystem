<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ShipmentAPIController extends Controller
{
    protected $database;
    protected $ref_table;
    protected $ref_table_firestore;
    
    
    public function __construct(Database $database)
    {
        $this->ref_table_firestore = app('firebase.firestore')->database()->collection('Shipment');
        $this->ref_table = "Shipment";
        $this->database = $database;
    }

    public function store(Request $request)
    {
        $shipmentID = $request->shipmentID;
        $reqDate = $request->requestDate;
        $location = $request->location;
        $shipDate = $request->shipDate;
        $description = $request->description;
        $quantity = $request->quantity;
        $status = 'Pending';

        //Status of Shipment: Pending, Arrive
        $postData = [
            "Quantity" => $quantity,
            "ShipID" => $shipmentID,
            "RequestDate" => $reqDate,
            "location" => $location,
            "ShipDate" => $shipDate,
            "Description" => $description,
            "Status" => $status
        ];

        //retrive data rows for the particular blood
        $reference = app('firebase.firestore')->database()->collection('inventoryList')->documents();
        $inventoryListData = collect($reference->rows());
        $packInfo = [];
        foreach ($inventoryListData as $item) {
            // $test = $item->data();
            // $packInfo[] = $test;
            $packInfo[] = $item->data();
        }
        //FETCH ALL ROW DATA
        $listInfo = [];
        foreach ($packInfo as $key => $value) {
            foreach ($value as $item => $item2) {
                $listInfo[$item] = $item2;
            }
        }

        //Filter Blood and Status is Available
        $infoAP = $this->filterBlood($listInfo, 'aPositive');
        $infoAN = $this->filterBlood($listInfo, 'aNegative');
        $infoBP = $this->filterBlood($listInfo, 'bPositive');
        $infoBN = $this->filterBlood($listInfo, 'bNegative');
        $infoOP = $this->filterBlood($listInfo, 'oPositive');
        $infoON = $this->filterBlood($listInfo, 'oNegative');
        $infoABP = $this->filterBlood($listInfo, 'abPositive');
        $infoABN = $this->filterBlood($listInfo, 'abNegative');

        //Sort based on the expiration date
        $infoAP = $this->sortBasedOnDate($infoAP);
        $infoAN = $this->sortBasedOnDate($infoAN);
        $infoBP = $this->sortBasedOnDate($infoBP);
        $infoBN = $this->sortBasedOnDate($infoBN);
        $infoOP = $this->sortBasedOnDate($infoOP);
        $infoON = $this->sortBasedOnDate($infoON);
        $infoABP = $this->sortBasedOnDate($infoABP);
        $infoABN = $this->sortBasedOnDate($infoABN);

        //Assign Shipment Blood
        $bloodAP = $this->changeStatus(array_slice($infoAP, 0, $quantity['aPositive']), $shipmentID);
        $bloodAN = $this->changeStatus(array_slice($infoAN, 0, $quantity['aNegative']), $shipmentID);

        $bloodBP = $this->changeStatus(array_slice($infoBP, 0, $quantity['bPositive']), $shipmentID);
        $bloodBN = $this->changeStatus(array_slice($infoBN, 0, $quantity['bNegative']), $shipmentID);

        $bloodOP = $this->changeStatus(array_slice($infoOP, 0, $quantity['oPositive']), $shipmentID);
        $bloodON = $this->changeStatus(array_slice($infoON, 0, $quantity['oNegative']), $shipmentID);

        $bloodABP = $this->changeStatus(array_slice($infoABP, 0, $quantity['abPositive']), $shipmentID);
        $bloodABN = $this->changeStatus(array_slice($infoABN, 0, $quantity['abNegative']), $shipmentID);

        $this->updateList($bloodAP);
        $this->updateList($bloodAN);
        $this->updateList($bloodBP);
        $this->updateList($bloodBN);
        $this->updateList($bloodOP);
        $this->updateList($bloodON);
        $this->updateList($bloodABP);
        $this->updateList($bloodABN);



        //Save inventory List
         $this->ref_table_firestore->newDocument()->set($postData);
         $postRef = $this->database->getReference($this->ref_table)->push($postData);

         //save shipment

        return;

         

    }

    public function show(Request $request)
    {
        $reference = $this->ref_table_firestore->documents();
        $data = collect($reference->rows());
        $info = [];
        foreach($data as $d){
            $info[] = $d->data();
        }
        // $shipInfo = [];
        // foreach($info as $r => $rKey){
        //     foreach($rKey as $key => $value){
        //         $shipInfo[$key] = $value;
        //     }
        // }
        // $sumOfQuantity = 0;
        // foreach($shipInfo['Quantity'] as $i => $value){
        //     $sumOfQuantity += $value;
        // }

        
        return view('BackEnd.JenSien.viewShipment')
        ->with('shipInfo', $info);
    }

    public function filterBlood($list, $bloodType_1){
        $info = [];
        foreach ($list as $key => $item) {

            if($item['bloodType'] === $bloodType_1){
                if ($item['status'] === 'Available')
                    $info[$key] = $item;
            }
        }
        krsort($info);
        return $info;
    }

    public function sortBasedOnDate($bloodList)
    {
        uasort($bloodList, function ($a, $b) {
            return strcmp($a['expirationDate'], $b['expirationDate']);
        });
        return $bloodList;
    }

    public function changeStatus($list, $shipmentID)
    {
        foreach ($list as $key => &$i) {
            $i['status'] = 'Shipment';
            $i['ShipmentID'] = $shipmentID;
        }
        return $list;
    }

    public function updateList($list)
    {
        $reference = app("firebase.firestore")->database()->collection("inventoryList")->documents();
        //Upload to inventoryList
        foreach ($reference as $r) {
            $rData = $r->data();
            foreach ($rData as $key => $value) {
                // Check if the list ID is present in array
                if (isset($list[$key])) {
                    // If the document ID is present 
                    $document_Id = $r->reference()->id();
                    $firestore = app('firebase.firestore')->database()->collection('inventoryList')->document($document_Id);
                    $firestore->update([
                        ['path' => $key, 'value' => $list[$key]]
                    ]);
                }
            }
        }
    }
}
