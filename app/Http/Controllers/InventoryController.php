<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Events\RentalActivityOccurred;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class InventoryController extends Controller
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
        // return $inventoryList;
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

        $id = $request->inventoryID;
        $eventID = $request->eventID;
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

        if ($postRef) {
            return redirect('edit-form-display')->with('status', 'Editted Successfully');
        } else {
            return redirect('edit-form-display')->with('status', 'Editted Failed');
        }
        // return redirect('getInventoryID')->with('inventoryID', $id);
    }

    //When the user access to the page
    public function displayInventoryForm()
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }

        return view('BackEnd.JenSien.editStock')
            ->with('inventoryInfo', $this->getAllInventory())
            ->with('eventInfo', $this->getEventInfo())
            ->with('edit_InventoryID', null)
            ->with('inventoryList', $this->showInventoryList())
            ->with('reqInventoryID', 'default');
    }

    // When the user selected the inventory id
    // public function show(Request $request)
    // {
    //     $inventoryID = $request->inventoryID;
    //     $expirationDate = [
    //         'aPositive' => null,
    //         'aNegative' => null,
    //         'bPositive' => null,
    //         'bNegative' => null,
    //         'oPositive' => null,
    //         'oNegative' => null,
    //         'abPositive' => null,
    //         'abNegative' => null
    //     ];
    //     $returnData = null;
    //     if ($this->database->getReference($this->ref_table_inventories)->getChild($inventoryID)->getValue() != null) {
    //         $returnData[$inventoryID] = $this->database->getReference($this->ref_table_inventories)->getChild($inventoryID)->getValue();
    //     }

    //     if ($returnData != null) {
    //         foreach ($expirationDate as $bloodType => $expDate) {
    //             foreach ($returnData as $id => $item) {
    //                 foreach ($item['bloodInfo'] as $bloodID => $value) {
    //                     if ($bloodType == $value['bloodType']) {
    //                         $expirationDate[$bloodType] = $value['expirationDate'];
    //                     }
    //                 }
    //                 $returnData[$id]['expirationDate'] = $expirationDate;
    //             }
    //         }
    //     }

    //     return view('BackEnd.JenSien.editStock')->with('inventoryInfo', $this->getAllInventory())->with('eventInfo', $this->getEventInfo())->with('edit_InventoryID', $returnData)->with('reqInventoryID', $inventoryID);
    // }

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

    public function getAllInventory()
    {
        if ($this->database->getReference($this->ref_table_inventories)->getValue() == null)
            return null;

        return array_reverse($this->database->getReference($this->ref_table_inventories)->getValue(), true);
    }
    public function shipOut()
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }
        $data = $this->database->getReference($this->ref_table_inventories)->getValue();
        $info = null;
        //SHOW NO RECORD PAGE --- TODO
        if ($data == null) {
            return view('BackEnd.JenSien.viewStock')->with('info', $info);
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

        $shipmentID = $this->idGenerator('S', 'Shipment');
        $hospitalList = $this->database->getReference($this->ref_table_hospital)->getValue();
        return view('BackEnd.JenSien.stockOut')->with('shipmentID', $shipmentID)->with('hospitalList', $hospitalList)->with('max', $status_info);
    }

    public function create()
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }
        $newID = $this->idGenerator('I', $this->ref_table_inventories);
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        return view("BackEnd.JenSien.stockIn")->with("newID", $newID)->with("eventInfo", $this->getEventInfo())->with('todayDate', $date);
    }

    public function getEventInfo()
    {
        return $this->database->getReference('Events')->getValue();
    }

    public function store(Request $request)
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }
        $inventoryID = $request->inventoryID;
        $eventID = $request->eventID;
        $date = $request->inventoryDate;
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

        $err = $this->getErrMessage($quantity, $expirationDate, $eventID);
        if (!empty($err)) {
            return redirect('add-inventory')->with('err', $err);
        }

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

        $postData = [
            'quantity' => $quantity,
            'eventID' => $eventID,
            'bloodInfo' => $bloodInfo,
            'date' => $date
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
        if ($this->verifyPermission() != true) {
            return view('BackEnd.JenSien.permissionDenied');
        }
        $data = $this->database->getReference($this->ref_table_inventories)->getValue();
        $info = null;
        //SHOW NO RECORD PAGE --- TODO
        if ($data == null) {
            return view('BackEnd.JenSien.viewStock')->with('info', $info);
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
            'bloodTypeAB' => $status_info_AB,
            'MIN_QUANTITY' => $this->MIN_QUANTITY
        ];

        $numOfBlood = $this->getNumOfBlood($info);
        $totalNumOfBlood = $this->getTotalNumOfBlood($numOfBlood);

        return view('BackEnd.JenSien.viewStock')
            ->with('numOfBlood', $numOfBlood)
            ->with('totalNumOfBlood', $totalNumOfBlood)
            ->with('info', $info)
            ->with('status_info', $status_info);
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

    //-----------------------------------------
    //              Validation  
    //-----------------------------------------
    public function getErrMessage($quantity, $expiredDate, $event)
    {
        $err = [];

        if (!$this->validQuantity($quantity))
            $err[] = 'Quantity cannot be Negative Value and only can be number';

        if (!$this->validDate($expiredDate))
            $err[] = 'Date range cannot exist 2 weeks';

        if (!$this->isAllNotZero($quantity))
            $err[] = 'All quantity is zero. Please insert some quantity';

        if (!$this->isEvent($event)) {
            $err[] = 'Please Select an Event.';
        }

        return $err;
    }
}
