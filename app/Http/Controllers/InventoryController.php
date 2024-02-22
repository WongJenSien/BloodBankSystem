<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class InventoryController extends Controller
{
    protected $database;
    protected $ref_table;
    protected $ref_table_firestore;
    public function __construct(Database $database)
    {
        $this->ref_table_firestore = app('firebase.firestore')->database()->collection('Inventories');
        $this->ref_table = "Inventories";
        $this->database = $database;
    }

    public function shipOut()
    {
        return view('BackEnd.JenSien.stockOut');
    }

    public function create()
    {
        $newID = $this->idGenerator();
        $eventID = $this->getEventID();
        return view("BackEnd.JenSien.stockIn")->with("newID", $newID);;
    }

    public function store(Request $request)
    {
        $inventoryID = $request->inventoryID;
        $expirationDate = array(
            'exDate_A_P' => $request->expiredDate_A_P,
            'exDate_A_N' => $request->expiredDate_A_N,

            'exDate_B_P' => $request->expiredDate_B_P,
            'exDate_B_N' => $request->expiredDate_B_N,

            'exDate_O_P' => $request->expiredDate_O_P,
            'exDate_O_N' => $request->expiredDate_O_N,

            'exDate_AB_P' => $request->expiredDate_AB_P,
            'exDate_AB_N' => $request->expiredDate_AB_N
        );

        $status = "Available"; //By Default
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

        $eventID = $request->eventID;

        $postData = [
            'inventoryID' => $inventoryID,
            'expirationDate' => $expirationDate,
            'status' => $status,
            'quantity' => $quantity,
            'eventID' => $eventID
        ];

        $this->ref_table_firestore->newDocument()->set($postData);
        $postRef = $this->database->getReference($this->ref_table)->push($postData);

        if($postRef){
            return redirect('view-inventory')->with('status', 'Added Successfully');
        }else{
            return redirect('view-inventory')->with('status', 'Added Failed');
        }


    }

    public function show(Request $request)
    {
        // $reference = $this->database->getReference($this->ref_table)->getValue();
        // return view('BackEnd.JenSien.viewStock', compact('reference'));

        $reference = $this->ref_table_firestore->documents();
        
        $data = collect($reference->rows());

        $numOfBlood = $this->getNumOfBlood($data);
        $totalNumOfBlood = $this->getTotalNumOfBlood($numOfBlood);

        return view('BackEnd.JenSien.viewStock')
        ->with('numOfBlood',$numOfBlood)
        ->with('totalNumOfBlood',$totalNumOfBlood);

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

    public function idGenerator(){

        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        //GET LATEST RECORD
        $reference = $this->ref_table_firestore->orderBy('inventoryID', 'DESC')->limit(1)->documents();
        $lastRecord = collect($reference->rows());

        //if no last record
        if($lastRecord->isEmpty()){
            $newID = "I" . substr($year,-2) . sprintf("%02s", $month) . "001";
        }else{
          $newID = $lastRecord->first()["inventoryID"];
          $last = substr($newID, -3);
          $newNum = intval($last) + 1;
          
          $newID = "I" . substr($year,-2) . sprintf("%02s", $month) . sprintf("%03d", $newNum);;
        }
        return $newID;
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

}
