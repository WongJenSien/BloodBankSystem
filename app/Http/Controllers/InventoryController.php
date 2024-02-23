<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class InventoryController extends Controller
{
    protected $database;
    protected $ref_table_inventories;
    protected $ref_table_inventoriesList;
    protected $ref_table_firestore_inventories;
    protected $ref_table_firestore_inventoriesList;
    public function __construct(Database $database)
    {
        $this->ref_table_firestore_inventories = app('firebase.firestore')->database()->collection('Inventories');
        $this->ref_table_firestore_inventoriesList = app('firebase.firestore')->database()->collection('inventoryList');
        $this->ref_table_inventories = "Inventories";
        $this->ref_table_inventoriesList = "inventoryList";
        $this->database = $database;
    }

    public function shipOut()
    {
        $shipmentID = $this->idGenerator('S', 'Shipment', 'shipmentID');
        return view('BackEnd.JenSien.stockOut')->with('shipmentID', $shipmentID);
    }

    public function create()
    {
        $newID = $this->idGenerator('I', $this->ref_table_inventories, 'inventoryID');
        $eventInfo = $this->getEventInfo();
        return view("BackEnd.JenSien.stockIn")->with("newID", $newID)->with("eventInfo", $eventInfo);
    }

    public function store(Request $request)
    {

        $inventoryID = $request->inventoryID;
        $expirationDate = [
            'aPositive' => $request->expiredDate_A_P,
            'aNegative' => $request->expiredDate_A_N,

            'bPositive' => $request->expiredDate_B_P,
            'bNegative' => $request->expiredDate_B_N,

            'oPositive' => $request->expiredDate_O_P,
            'oNegative' => $request->expiredDate_O_N,

            'abPositive' => $request->expiredDate_AB_P,
            'abNegative' => $request->expiredDate_AB_N
        ];

        $status = "Available"; //By Default
        $quantity = [
            'aPositive' => $request->aPositive,
            'aNegative' => $request->aNegative,

            'bPositive' => $request->bPositive,
            'bNegative' => $request->bNegative,

            'oPositive' => $request->oPositive,
            'oNegative' => $request->oNegative,

            'abPositive' => $request->abPositive,
            'abNegative' => $request->abNegative
        ];

        //InventoryList
        // Blood Type ID: I2402001-A001
        $bloodID = [
            "aPositive" => $this->bloodTypeID($quantity['aPositive'],$inventoryID, "AP"),
            "aNegative" => $this->bloodTypeID($quantity['aNegative'],$inventoryID, "AN"),

            "bPositive" => $this->bloodTypeID($quantity['bPositive'],$inventoryID, "BP"),
            "bNegative" => $this->bloodTypeID($quantity['bNegative'],$inventoryID, "BN"),

            "oPositive" => $this->bloodTypeID($quantity['oPositive'],$inventoryID, "OP"),
            "oNegative" => $this->bloodTypeID($quantity['oNegative'],$inventoryID, "ON"),

            "abPositive" => $this->bloodTypeID($quantity['abPositive'],$inventoryID, "ABP"),
            "abNegative" => $this->bloodTypeID($quantity['abNegative'],$inventoryID, "ABN"),
        ];

        $bloodInfo = [];
        foreach ($bloodID as $bloodType => $bloodTypeIDs) {
            foreach ($bloodTypeIDs as $id) {
                $bloodInfo[] = [
                    'id' => $id,
                    'bloodType' => $bloodType,
                    'status' => $status,
                    'inventoryID' => $inventoryID,
                    'expirationDate' => $expirationDate[$bloodType],
                ];
            }
        }      
        //Save inventory List
        $this->ref_table_firestore_inventoriesList->newDocument()->set($bloodInfo);
        $this->database->getReference($this->ref_table_inventoriesList)->push($bloodInfo);

        $eventID = $request->eventID;

        $postData = [
            'inventoryID' => $inventoryID,
            'quantity' => $quantity,
            'eventID' => $eventID
        ];

        $this->ref_table_firestore_inventories->newDocument()->set($postData);
        $postRef = $this->database->getReference($this->ref_table_inventories)->push($postData);

        if($postRef){
            return redirect('view-inventory')->with('status', 'Added Successfully');
        }else{
            return redirect('view-inventory')->with('status', 'Added Failed');
        }


    }

    public function show(Request $request)
    {

        $reference = $this->ref_table_firestore_inventories->documents();
        $data = collect($reference->rows());

        $reference = $this->ref_table_firestore_inventoriesList->documents();
        $list = collect($reference->rows());

        $sortList = [];
        foreach($list as $l){
            $sortList[] = $l->data();
        }
dd($sortList);

        $numOfBlood = $this->getNumOfBlood($data);
        $totalNumOfBlood = $this->getTotalNumOfBlood($numOfBlood);
        


        return view('BackEnd.JenSien.viewStock')
        ->with('numOfBlood',$numOfBlood)
        ->with('totalNumOfBlood',$totalNumOfBlood)
        ->with('sortList', $sortList);

    }

    public function edit(Request $request)
    {
    }
    public function update(Request $request)
    {
    }
    public function destroy(Request $request)
    {
       
    }
    public function restore(Request $request)
    {
    }

    public function idGenerator($letter, $ref_collection, $item){

        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        //GET LATEST RECORD
        $reference = app('firebase.firestore')->database()->collection($ref_collection)->orderBy($item, 'DESC')->limit(1)->documents();
        $lastRecord = collect($reference->rows());

        //if no last record
        if($lastRecord->isEmpty()){
            $newID = $letter . substr($year,-2) . sprintf("%02s", $month) . "001";
        }else{
          $newID = $lastRecord->first()["inventoryID"];
          $last = substr($newID, -3);
          $newNum = intval($last) + 1;
          
          $newID = $letter . substr($year,-2) . sprintf("%02s", $month) . sprintf("%03d", $newNum);
        }
        return $newID;
    }

    public function bloodTypeID($quantity, $inventoryID, $bloodType){
        $bloodID = [];
        for($a = 0; $a < $quantity; $a++){
            $id = $inventoryID . "-". $bloodType . sprintf("%03d", $a+1);
            $bloodID[] = $id;
        }
        return $bloodID;
    }

    public function getNumOfBlood($data){
        $numOfBlood=[
            'aPositive' => 0,
            'aNegative' => 0,
            'bPositive' => 0,
            'bNegative' => 0,
            'oPositive' => 0,
            'oNegative' => 0,
            'abPositive' => 0,
            'abNegative' => 0
        ];

        foreach($data as $d){
            $numOfBlood['aPositive'] += $d->data()['quantity']['aPositive'];
            $numOfBlood['aNegative'] += $d->data()['quantity']['aNegative'];

            $numOfBlood['bPositive'] += $d->data()['quantity']['bPositive'];
            $numOfBlood['bNegative'] += $d->data()['quantity']['bNegative'];

            $numOfBlood['oPositive'] += $d->data()['quantity']['oPositive'];
            $numOfBlood['oNegative'] += $d->data()['quantity']['oNegative'];

            $numOfBlood['abPositive'] += $d->data()['quantity']['abPositive'];
            $numOfBlood['abNegative'] += $d->data()['quantity']['abNegative'];    
        }

        return $numOfBlood;
    }

    public function getTotalNumOfBlood($numOfBlood){
        
        $totalNumOfBlood = [
            'Blood_A' => 0,
            'Blood_B' => 0,
            'Blood_O' => 0,
            'Blood_AB' => 0
        ];

        $totalNumOfBlood['Blood_A'] = $numOfBlood['aPositive'] + $numOfBlood['aNegative'];
        $totalNumOfBlood['Blood_B'] = $numOfBlood['bPositive'] + $numOfBlood['bNegative'];
        $totalNumOfBlood['Blood_O'] = $numOfBlood['oPositive'] + $numOfBlood['oNegative'];
        $totalNumOfBlood['Blood_AB'] = $numOfBlood['abPositive'] + $numOfBlood['abNegative'];

        return $totalNumOfBlood;
    }

    public function getEventInfo(){
        $reference = app("firebase.firestore")->database()->collection("Events")->orderBy('EventID', 'DESC')->documents();
        $data = collect($reference->rows());
        return $data;
    }
}
