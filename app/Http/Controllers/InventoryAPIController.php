<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryAPIController extends Controller
{

    public function showInventoryList()
    {
        $inventoryList = $this->getAllInventory();

        if ($this->getAllInventory() == null) {
            return null;
        }

        $quantity = [
            'BloodType_A' => 0,
            'BloodType_B' => 0,
            'BloodType_O' => 0,
            'BloodType_AB' => 0
        ];



        foreach ($inventoryList as $id => $info) {
            // Count Quantity
            $quantity = [
                'BloodType_A' => 0,
                'BloodType_B' => 0,
                'BloodType_O' => 0,
                'BloodType_AB' => 0
            ];

            foreach ($info['quantity'] as $key => $numbers) {
                if ($key == 'aNegative' || $key == 'aPositive') {
                    $quantity['BloodType_A'] += $numbers;
                }
                if ($key == 'bNegative' || $key == 'bPositive') {
                    $quantity['BloodType_B'] += $numbers;
                }
                if ($key == 'oNegative' || $key == 'oPositive') {
                    $quantity['BloodType_O'] += $numbers;
                }
                if ($key == 'abNegative' || $key == 'abPositive') {
                    $quantity['BloodType_AB'] += $numbers;
                }
            }
            $inventoryList[$id]['shipQuantity'] = $this->getShipQuantity($id);
            $inventoryList[$id]['eventName'] =  $this->database->getReference($this->ref_table_event)->getChild($info['eventID'])->getChild('eventName')->getValue();
            $inventoryList[$id]['bloodType'] = $quantity;
            $inventoryList[$id]['expirationDate'] = $this->getExpDate($id);
        }

        return $inventoryList;
    }

    public function getExpDate($id)
    {
        $expirationDate = [
            'aPositive' => null,
            'aNegative' => null,
            'bPositive' => null,
            'bNegative' => null,
            'oPositive' => null,
            'oNegative' => null,
            'abPositive' => null,
            'abNegative' => null
        ];
        $inventoryInfo = null;
        if ($this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue() != null) {
            $inventoryInfo[$id] = $this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue();
        }

        if ($inventoryInfo != null) {
            foreach ($expirationDate as $bloodType => $expDate) {
                foreach ($inventoryInfo as $id => $item) {
                    foreach ($item['bloodInfo'] as $bloodID => $value) {
                        if ($bloodType == $value['bloodType']) {
                            $expirationDate[$bloodType] = $value['expirationDate'];
                        }
                    }
                }
            }
        }

        return $expirationDate;
    }
    public function getShipQuantity($id)
    {
        $shipQuantity = [
            'aPositive' => 0,
            'aNegative' => 0,
            'bPositive' => 0,
            'bNegative' => 0,
            'oPositive' => 0,
            'oNegative' => 0,
            'abPositive' => 0,
            'abNegative' => 0,
        ];
        if ($this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue() != null) {
            $list = $this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue();
            foreach ($list['bloodInfo'] as $key => $value) {
                if ($value['status'] != 'Available') {
                    foreach ($shipQuantity as $bloodType => $quantity) {
                        if ($value['bloodType'] == $bloodType) {
                            $shipQuantity[$bloodType]++;
                        }
                    }
                }
            }
        }
        return $shipQuantity;
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $eventID = $request->eventID;
        // $expirationDate = [
        //     'aPositive' => $request->expiredDate_A_P,
        //     'aNegative' => $request->expiredDate_A_N,

        //     'bPositive' => $request->expiredDate_B_P,
        //     'bNegative' => $request->expiredDate_B_N,

        //     'oPositive' => $request->expiredDate_O_P,
        //     'oNegative' => $request->expiredDate_O_N,

        //     'abPositive' => $request->expiredDate_AB_P,
        //     'abNegative' => $request->expiredDate_AB_N
        // ];

        $expirationDate = $request->expirationDate;
        $quantity = $request->quantity;
        // $quantity = [
        //     'aPositive' => $request->aPositive,
        //     'aNegative' => $request->aNegative,

        //     'bPositive' => $request->bPositive,
        //     'bNegative' => $request->bNegative,

        //     'oPositive' => $request->oPositive,
        //     'oNegative' => $request->oNegative,

        //     'abPositive' => $request->abPositive,
        //     'abNegative' => $request->abNegative
        // ];
        $oriBloodInfo = $this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue();
        // $oriShipQuantity = $this->getShipQuantity($id);
        $oriShipQuantity = [
            "aPositive" => 2,
            "aNegative" => 2,
            "bPositive" => 0,
            "bNegative" => 1,
            "oPositive" => 0,
            "oNegative" => 0,
            "abPositive" => 0,
            "abNegative" => 0
        ];
        //Original Blood Info
        // $oriBloodInfo = null;
        // if ($this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue() != null) {
        //     $oriBloodInfo = $this->database->getReference($this->ref_table_inventories)->getChild($id)->getValue();
        // }

        //Generate New Blood ID List
        foreach ($quantity as $bloodType => $number) {
            if (strtolower(substr($bloodType, 0, 2)) === 'ab') {
                $bloodElement = strtoupper(substr($bloodType, 0, 3));
            } else {
                $bloodElement = strtoupper(substr($bloodType, 0, 2));
            }
            //Regenerate Blood Info
            $bloodList[$bloodType] = $this->bloodTypeID($quantity[$bloodType], $id, $bloodElement);
        }


        //Assign Blood Info
        // If the Blood ID is exist Assign Original Blood Info into the new Blood List
        // If the Blood ID doesn't exist Assign default Data into the new Blood List
        foreach ($bloodList as $bloodType => $values) {
            $defaultData = [
                'bloodType' => $bloodType,
                'status' => 'Available',
                'expirationDate' => $expirationDate[$bloodType],
                'ShipmentID' => ''
            ];
            foreach ($values as $bloodID) {
                // dd($this->database->getReference($this->ref_table_inventories)->getChild($id)->getChild('bloodInfo')->getChild($bloodID)->getValue());
                $data = $this->database->getReference($this->ref_table_inventories)->getChild($id)->getChild('bloodInfo')->getChild($bloodID)->getValue();
                if ($data != null) {
                    $data['expirationDate'] = $expirationDate[$bloodType];
                    $bloodInfo[$bloodID] = $data;
                } else {
                    $bloodInfo[$bloodID] = $defaultData;
                }
            }
        }
        $editKey1 = 'bloodInfo';
        $editKey2 = 'eventID';
        $editKey3 = 'quantity';
        $postRef = $this->database->getReference($this->ref_table_inventories)->getChild($id)->update([$editKey1 => $bloodInfo, $editKey2 => $eventID, $editKey3 => $quantity]);
        return ['Edited Successfullly'];
        // return redirect('getInventoryID')->with('inventoryID', $id);
    }

    public function getEventInfo()
    {
        return $this->database->getReference('Events')->getValue();
    }

    //When the user access to the page
    public function displayInventoryForm()
    {

        $returnData = [
            'inventoryInfo' => $this->getAllInventory(),
            'eventInfo' => $this->getEventInfo(),
            'edit_InventoryID' => null,
            'inventoryList' => $this->showInventoryList(),
            'reqInventoryID' => 'default',
        ];
        return $returnData;
    }

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
            'status_info_AB' => $status_info_AB,
            'status_MIN_QUANTITY' => $this->MIN_QUANTITY
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
        $eventID = $request->eventID;
        $date = $request->inventoryDate;

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

        //VALIDATION
        $err = $this->getErrMessage($quantity, $epxDate, $eventID);
        if ($err != null) {
            return $err;
        }


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



        $postData = [
            'quantity' => $quantity,
            'eventID' => $eventID,
            'bloodInfo' => $bloodInfo,
            'date' => $date
        ];

        // $this->ref_table_firestore_inventories->newDocument()->set($postData);
        // $this->database->getReference($this->ref_table_inventories)->push($postData);
        $postRef = $this->database->getReference($this->ref_table_inventories . '/' . $inventoryID)->set($postData);
        return;
    }

    public function shipOut(Request $request)
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

        $returnData = [
            'shipmentID' => $this->idGenerator('S', 'Shipment'),
            'hospitalList' => $this->database->getReference($this->ref_table_hospital)->getValue(),
            'max' => $status_info
        ];
        return $returnData;
    }

    public function getAllInventory()
    {
        if ($this->database->getReference($this->ref_table_inventories)->getValue() == null)
            return null;

        return array_reverse($this->database->getReference($this->ref_table_inventories)->getValue(), true);
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

    public function bloodTypeID($quantity, $inventoryID, $bloodType)
    {
        $bloodID = [];
        for ($a = 0; $a < $quantity; $a++) {
            $id = $inventoryID . "-" . $bloodType . sprintf("%03d", $a + 1);
            $bloodID[] = $id;
        }
        return $bloodID;
    }
    // ------------------------------
    //          VALIDATION
    // ------------------------------



    public function getErrMessage($quantity, $epxDate, $eventID)
    {
        $err = [];

        if (!$this->validQuantity($quantity))
            $err[] = 'Quantity cannot be Negative Value and only can be number';

        if (!$this->validDate($epxDate))
            $err[] = 'Date range cannot exist 2 weeks';

        if (!$this->isAllNotZero($quantity))
            $err[] = 'All quantity is zero. Please insert some quantity';

        if (!$this->isEvent($eventID)) {
            $err[] = 'Please Select an Event.';
        }

        return $err;
    }
}
