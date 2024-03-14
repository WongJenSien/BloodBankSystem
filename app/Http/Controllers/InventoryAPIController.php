<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryAPIController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->database->getReference($this->ref_table_inventories)->getValue();
        //SHOW NO RECORD PAGE --- TODO
        if ($data == null) {
            return null;
        }
        
        foreach ($data as $key => $value) {
            foreach ($value['bloodInfo'] as $bKey => $bValue)
                $listInfo[$bKey] = $bValue;
        }

        //FILTER BLOOD TYPE
        $infoA = $this->filterBlood($listInfo, 'aPositive', 'aNegative');
        $infoB = $this->filterBlood($listInfo, 'bPositive', 'bNegative');
        $infoO = $this->filterBlood($listInfo, 'oPositive', 'oNegative');
        $infoAB = $this->filterBlood($listInfo, 'abPositive', 'abNegative');

        $package_info = [
            'infoA' => $infoA,
            'infoB' => $infoB,
            'infoO' => $infoO,
            'infoAB' => $infoAB
        ];

        //COUNT BLOOD STATUS
        $status_info_A = $this->countBlood($listInfo, 'aPositive', 'aNegative');
        $status_info_B = $this->countBlood($listInfo, 'bPositive', 'bNegative');
        $status_info_O = $this->countBlood($listInfo, 'oPositive', 'oNegative');
        $status_info_AB = $this->countBlood($listInfo, 'abPositive', 'abNegative');

        $package_status_info = [
            'status_info_A' => $status_info_A,
            'status_info_B' => $status_info_B,
            'status_info_O' => $status_info_O,
            'status_info_AB' => $status_info_AB
        ];


        $numOfBlood = $this->getNumOfBlood($package_info);
        $totalNumOfBlood = $this->getTotalNumOfBlood($numOfBlood);

        $returnData = [
            "numOfBlood" => $numOfBlood,
            "totalNumOfBlood" => $totalNumOfBlood,
            "package_info" => $package_info,
            "package_status_info" => $package_status_info
        ];

        return $returnData;
    }

    public function store(Request $request)
    {
        $inventoryID = $request->newID;
        $inventoryID = $inventoryID[0];
        $epxDate = $request->expiredDate;
        $quantity = $request->quantity;

        $status = "Available"; //By Default

        //INVENTORY LIST
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
                    'inventoryID' => $inventoryID,
                    'expirationDate' => $epxDate[$bloodType],
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
        // $this->database->getReference($this->ref_table_inventories)->push($postData);
        $postRef = $this->database->getReference($this->ref_table_inventories . '/' . $inventoryID)->set($postData);
        return [$eventID];
    }

    public function shipOut(Request $request)
    {
        $returnData = [
            'shipmentID' => $this->idGenerator('S', 'Shipment'),
            'hospitalList' => $this->database->getReference($this->ref_table_hospital)->getValue()
        ];
        return $returnData;
    }

    public function getNewId()
    {
        return [$this->idGenerator('I', $this->ref_table_inventories)];
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
        // foreach ($data as $d) {
        //     $numOfBlood['aPositive'] += $d->data()['quantity']['aPositive'];
        //     $numOfBlood['aNegative'] += $d->data()['quantity']['aNegative'];

        //     $numOfBlood['bPositive'] += $d->data()['quantity']['bPositive'];
        //     $numOfBlood['bNegative'] += $d->data()['quantity']['bNegative'];

        //     $numOfBlood['oPositive'] += $d->data()['quantity']['oPositive'];
        //     $numOfBlood['oNegative'] += $d->data()['quantity']['oNegative'];

        //     $numOfBlood['abPositive'] += $d->data()['quantity']['abPositive'];
        //     $numOfBlood['abNegative'] += $d->data()['quantity']['abNegative'];
        // }

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
    // public function idGenerator($letter, $ref_collection)
    // {

    //     $today = Carbon::now();
    //     $year = $today->year;
    //     $month = $today->month;

    //     //GET LATEST RECORD
    //     // $reference = app('firebase.firestore')->database()->collection($ref_collection)->orderBy($item, 'DESC')->limit(1)->documents();
    //     // $lastRecord = collect($reference->rows());
    //     $lastID = $this->database->getReference($ref_collection)->orderByKey()->limitToLast(1)->getValue();
    //     if ($lastID != null) {
    //         $lastID = array_keys($lastID)[0];
    //     }


    //     //if no last record
    //     if ($lastID === null || substr($lastID, strlen($letter), 4) != substr($year, -2) . sprintf("%02s", $month)) {
    //         $newID = $letter . substr($year, -2) . sprintf("%02s", $month) . "001";
    //     } else {
    //         $newID = $lastID;
    //         $last = substr($newID, -3);
    //         $newNum = intval($last) + 1;

    //         $newID = $letter . substr($year, -2) . sprintf("%02s", $month) . sprintf("%03d", $newNum);
    //     }
    //     return $newID;
    // }

    public function bloodTypeID($quantity, $inventoryID, $bloodType)
    {
        $bloodID = [];
        for ($a = 0; $a < $quantity; $a++) {
            $id = $inventoryID . "-" . $bloodType . sprintf("%03d", $a + 1);
            $bloodID[] = $id;
        }
        return $bloodID;
    }
}
