<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class InventoryController extends Controller
{
    protected $database;
    protected $ref_table_inventories;
    // protected $ref_table_inventoriesList;
    // protected $ref_table_firestore_inventories;
    // protected $ref_table_firestore_inventoriesList;
    public function __construct(Database $database)
    {
        // $this->ref_table_firestore_inventories = app('firebase.firestore')->database()->collection('Inventories');
        // $this->ref_table_firestore_inventoriesList = app('firebase.firestore')->database()->collection('inventoryList');
        $this->ref_table_inventories = "Inventories";
        // $this->ref_table_inventoriesList = "inventoryList";
        $this->database = $database;
    }

    public function shipOut()
    {
        $shipmentID = $this->idGenerator('S', 'Shipment');
        return view('BackEnd.JenSien.stockOut')->with('shipmentID', $shipmentID);
    }

    public function create()
    {
        $newID = $this->idGenerator('I', $this->ref_table_inventories);
        // dd($this->idGenerator('I', $this->ref_table_inventories, 'inventoryID'));
        // $newID = $this->database->getReference($this->ref_table_inventories)->orderByKey()->limitToLast(1)->getValue();
        $eventInfo = $this->database->getReference('Events')->getValue();
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
            "aPositive" => $this->bloodTypeID($quantity['aPositive'], $inventoryID, "AP"),
            "aNegative" => $this->bloodTypeID($quantity['aNegative'], $inventoryID, "AN"),

            "bPositive" => $this->bloodTypeID($quantity['bPositive'], $inventoryID, "BP"),
            "bNegative" => $this->bloodTypeID($quantity['bNegative'], $inventoryID, "BN"),

            "oPositive" => $this->bloodTypeID($quantity['oPositive'], $inventoryID, "OP"),
            "oNegative" => $this->bloodTypeID($quantity['oNegative'], $inventoryID, "ON"),

            "abPositive" => $this->bloodTypeID($quantity['abPositive'], $inventoryID, "ABP"),
            "abNegative" => $this->bloodTypeID($quantity['abNegative'], $inventoryID, "ABN"),
        ];

        $bloodInfo = [];
        foreach ($bloodID as $bloodType => $bloodTypeIDs) {
            foreach ($bloodTypeIDs as $id) {
                $bloodInfo[$id] = [
                    'bloodType' => $bloodType,
                    'status' => $status,
                    'expirationDate' => $expirationDate[$bloodType],
                    'ShipmentID' => ''
                ];
            }
        }


        //Save inventory List
        // $this->ref_table_firestore_inventoriesList->newDocument()->set($bloodInfo);
        // $this->database->getReference($this->ref_table_inventoriesList)->push($bloodInfo);

        $eventID = $request->eventID;

        $postData = [
            'quantity' => $quantity,
            'eventID' => $eventID,
            'bloodInfo' => $bloodInfo
        ];

        // $this->ref_table_firestore_inventories->newDocument()->set($postData);
        $postRef = $this->database->getReference($this->ref_table_inventories . '/' . $inventoryID)->set($postData);


        if ($postRef) {
            return redirect('view-inventory')->with('status', 'Added Successfully');
        } else {
            return redirect('view-inventory')->with('status', 'Added Failed');
        }
    }

    public function index(Request $request)
    {

        // $reference = $this->ref_table_firestore_inventories->documents();
        // $data = collect($reference->rows());

        // $reference = $this->ref_table_firestore_inventoriesList->documents();
        // $list = collect($reference->rows());

        // $packInfo = [];
        // foreach ($list as $item) {
        //     $packInfo[] = $item->data();
        // }

        // $listInfo = [];
        // foreach ($packInfo as $key => $value) {
        //     foreach ($value as $item => $item2) {
        //         $listInfo[$item] = $item2;
        //     }
        // }

        $data = $this->database->getReference($this->ref_table_inventories)->getValue();

        //SHOW NO RECORD PAGE --- TODO
        if ($data == null) {
            return view('BackEnd.JenSien.viewStock');
        }

        foreach ($data as $key => $value) {
            foreach ($value['bloodInfo'] as $bKey => $bValue)
                $listInfo[$bKey] = $bValue;
        }


        //FILTER BLOOD TYPE
        $info = [];
        $infoA = $this->filterBlood($listInfo, 'aPositive', 'aNegative');
        $infoB = $this->filterBlood($listInfo, 'bPositive', 'bNegative');
        $infoO = $this->filterBlood($listInfo, 'oPositive', 'oNegative');
        $infoAB = $this->filterBlood($listInfo, 'abPositive', 'abNegative');

        $info = [
            'bloodTypeA' => $infoA,
            'bloodTypeB' => $infoB,
            'bloodTypeO' => $infoO,
            'bloodTypeAB' => $infoAB
        ];

        //COUNT BLOOD STATUS
        $status_info = [];
        $status_info_A = $this->countBlood($listInfo, 'aPositive', 'aNegative');
        $status_info_B = $this->countBlood($listInfo, 'bPositive', 'bNegative');
        $status_info_O = $this->countBlood($listInfo, 'oPositive', 'oNegative');
        $status_info_AB = $this->countBlood($listInfo, 'abPositive', 'abNegative');

        $status_info = [
            'bloodTypeA' => $status_info_A,
            'bloodTypeB' => $status_info_B,
            'bloodTypeO' => $status_info_O,
            'bloodTypeAB' => $status_info_AB
        ];



        $numOfBlood = $this->getNumOfBlood($info);
        $totalNumOfBlood = $this->getTotalNumOfBlood($numOfBlood);

        return view('BackEnd.JenSien.viewStock')
            ->with('numOfBlood', $numOfBlood)
            ->with('totalNumOfBlood', $totalNumOfBlood)
            ->with('info', $info)
            ->with('status_info', $status_info);
    }

    public function idGenerator($letter, $ref_collection)
    {

        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        //GET LATEST RECORD
        // $reference = app('firebase.firestore')->database()->collection($ref_collection)->orderBy($item, 'DESC')->limit(1)->documents();
        // $lastRecord = collect($reference->rows());
        $lastID = $this->database->getReference($ref_collection)->orderByKey()->limitToLast(1)->getValue();
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

    public function bloodTypeID($quantity, $inventoryID, $bloodType)
    {
        $bloodID = [];
        for ($a = 0; $a < $quantity; $a++) {
            $id = $inventoryID . "-" . $bloodType . sprintf("%03d", $a + 1);
            $bloodID[] = $id;
        }
        return $bloodID;
    }

    public function getNumOfBlood($data)
    {
        $numOfBlood = [
            'aPositive' => 0,
            'aNegative' => 0,
            'bPositive' => 0,
            'bNegative' => 0,
            'oPositive' => 0,
            'oNegative' => 0,
            'abPositive' => 0,
            'abNegative' => 0
        ];

        foreach ($data as $d) {
            foreach ($d as $key => $value) {
                switch ($value['bloodType']) {
                    case 'aPositive':
                        $numOfBlood['aPositive']++;
                        break;
                    case 'aNegative':
                        $numOfBlood['aNegative']++;
                        break;
                    case 'bPositive':
                        $numOfBlood['bPositive']++;
                        break;
                    case 'bNegative':
                        $numOfBlood['bNegative']++;
                        break;
                    case 'oPositive':
                        $numOfBlood['oPositive']++;
                        break;
                    case 'oNegative':
                        $numOfBlood['oNegative']++;
                        break;
                    case 'abPositive':
                        $numOfBlood['abPositive']++;
                        break;
                    case 'abNegative':
                        $numOfBlood['abNegative']++;
                        break;
                }
            }
        }

        return $numOfBlood;
    }

    public function getTotalNumOfBlood($numOfBlood)
    {

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

    public function filterBlood($list, $bloodType_1, $bloodType_2)
    {
        $info = [];
        foreach ($list as $key => $item) {

            if ($item['bloodType'] === $bloodType_1 || $item['bloodType'] === $bloodType_2) {
                $info[$key] = $item;
            }
        }
        krsort($info);
        return $info;
    }

    public function countBlood($list, $bloodType_1, $bloodType_2)
    {
        $info = [
            'Available_P' => 0,
            'Available_N' => 0,
            'Shipment_P' => 0,
            'Shipment_N' => 0,
        ];

        foreach ($list as $key => $item) {
            if ($item['status'] === 'Available' && $item['bloodType'] === $bloodType_1) {
                $info['Available_P']++;
            }
            if ($item['status'] === 'Available' && $item['bloodType'] === $bloodType_2) {
                $info['Available_N']++;
            }

            if ($item['status'] === 'Shipment' && $item['bloodType'] === $bloodType_1) {
                $info['Shipment_P']++;
            }
            if ($item['status'] === 'Shipment' && $item['bloodType'] === $bloodType_2) {
                $info['Shipment_N']++;
            }
        }
        return $info;
    }
}
