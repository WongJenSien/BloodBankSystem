<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ShipmentAPIController extends Controller
{
    protected $database;
    protected $ref_table_shipment;
    protected $ref_table_inventories;
    protected $ref_table_event;
    protected $ref_table_firestore;


    public function __construct(Database $database)
    {
        $this->ref_table_firestore = app('firebase.firestore')->database()->collection('Shipment');
        $this->ref_table_shipment = "Shipment";
        $this->ref_table_inventories = 'Inventories';
        $this->ref_table_event= 'Events';
        $this->database = $database;
    }

    public function index(Request $request)
    {
        $info[] = $this->database->getReference($this->ref_table_shipment)->getValue();
        $info = reset($info);
        // $reference = $this->ref_table_firestore->documents();
        // $data = collect($reference->rows());
        // $info = [];
        // foreach ($data as $d) {
        //     $info[] = $d->data();
        // }
        return $info;
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
            "RequestDate" => $reqDate,
            "location" => $location,
            "ShipDate" => $shipDate,
            "Description" => $description,
            "Status" => $status
        ];

        $data = $this->database->getReference($this->ref_table_inventories)->getValue();

        foreach ($data as $key => $value) {
            foreach ($value['bloodInfo'] as $bKey => $bValue)
                $listInfo[$bKey] = $bValue;
        }

        //retrive data rows for the particular blood
        // $reference = app('firebase.firestore')->database()->collection('inventoryList')->documents();
        // $inventoryListData = collect($reference->rows());
        // $packInfo = [];
        // foreach ($inventoryListData as $item) {
        //     // $test = $item->data();
        //     // $packInfo[] = $test;
        //     $packInfo[] = $item->data();
        // }
        // //FETCH ALL ROW DATA
        // $listInfo = [];
        // foreach ($packInfo as $key => $value) {
        //     foreach ($value as $item => $item2) {
        //         $listInfo[$item] = $item2;
        //     }
        // }

        //Filter Blood and Status is Available
        $keyStatus = 'Available';
        $infoAP = $this->filterBlood($listInfo, 'aPositive', $keyStatus);
        $infoAN = $this->filterBlood($listInfo, 'aNegative', $keyStatus);
        $infoBP = $this->filterBlood($listInfo, 'bPositive', $keyStatus);
        $infoBN = $this->filterBlood($listInfo, 'bNegative', $keyStatus);
        $infoOP = $this->filterBlood($listInfo, 'oPositive', $keyStatus);
        $infoON = $this->filterBlood($listInfo, 'oNegative', $keyStatus);
        $infoABP = $this->filterBlood($listInfo, 'abPositive', $keyStatus);
        $infoABN = $this->filterBlood($listInfo, 'abNegative', $keyStatus);

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
        // $bloodAP = $this->changeStatus(array_slice($infoAP, 0, $quantity['aPositive']), $shipmentID);
        // $bloodAN = $this->changeStatus(array_slice($infoAN, 0, $quantity['aNegative']), $shipmentID);

        // $bloodBP = $this->changeStatus(array_slice($infoBP, 0, $quantity['bPositive']), $shipmentID);
        // $bloodBN = $this->changeStatus(array_slice($infoBN, 0, $quantity['bNegative']), $shipmentID);

        // $bloodOP = $this->changeStatus(array_slice($infoOP, 0, $quantity['oPositive']), $shipmentID);
        // $bloodON = $this->changeStatus(array_slice($infoON, 0, $quantity['oNegative']), $shipmentID);

        // $bloodABP = $this->changeStatus(array_slice($infoABP, 0, $quantity['abPositive']), $shipmentID);
        // $bloodABN = $this->changeStatus(array_slice($infoABN, 0, $quantity['abNegative']), $shipmentID);

        $bloodAP = array_slice($infoAP, 0, $quantity['aPositive']);
        $bloodAN = array_slice($infoAN, 0, $quantity['aNegative']);

        $bloodBP = array_slice($infoBP, 0, $quantity['bPositive']);
        $bloodBN = array_slice($infoBN, 0, $quantity['bNegative']);

        $bloodOP = array_slice($infoOP, 0, $quantity['oPositive']);
        $bloodON = array_slice($infoON, 0, $quantity['oNegative']);

        $bloodABP = array_slice($infoABP, 0, $quantity['abPositive']);
        $bloodABN = array_slice($infoABN, 0, $quantity['abNegative']);

        $this->updateList($bloodAP, $shipmentID);
        $this->updateList($bloodAN, $shipmentID);
        $this->updateList($bloodBP, $shipmentID);
        $this->updateList($bloodBN, $shipmentID);
        $this->updateList($bloodOP, $shipmentID);
        $this->updateList($bloodON, $shipmentID);
        $this->updateList($bloodABP, $shipmentID);
        $this->updateList($bloodABN, $shipmentID);



        //Save inventory List
        // $this->ref_table_firestore->newDocument()->set($postData);
        // $postRef = $this->database->getReference($this->ref_table_shipment)->push($postData);

        //save shipment
        $this->database->getReference($this->ref_table_shipment . '/' . $shipmentID)->set($postData);

        return;
    }

    public function show($id)
    {
        //Retreive Shipment Infomation
        $shipmentInfo = $this->database->getReference($this->ref_table_shipment)->getChild($id)->getValue();


        //Retreive Shipment Infomation
        // $reference = app('firebase.firestore')->database()->collection('Shipment')->documents();
        // $dataList = collect($reference->rows());
        // foreach ($dataList as $d) {
        //     if ($d->data()['ShipID'] == $id) {
        //         $shipmentInfo = $d->data();
        //     }
        // }


        //Retreive All Data FROM INVENTORY LIST
        // $reference = app('firebase.firestore')->database()->collection('inventoryList')->documents();
        // $dataList = collect($reference->rows());

        //FilterBlood
        // $inventoryID = [];
        // foreach ($dataList as $d) {
        //     foreach ($d->data() as $key => $value)
        //         if ($value['ShipmentID'] !== null && $value['ShipmentID'] === $id) {
        //             $listInfo[$key] = $value;
        //             if (!in_array($value['inventoryID'], $inventoryID)) {
        //                 $inventoryID[] = $value['inventoryID'];
        //             }
        //         }
        // }



        //Retreive inventory info
        // $reference = app('firebase.firestore')->database()->collection('Inventories')->documents();
        // $dataList = collect($reference->rows());

        // $data = [];
        // foreach ($dataList as $d) {
        //     $data[] = $d->data();
        // }
        // $eventID = [];
        // foreach ($data as $key) {
        //     foreach ($inventoryID as $k => $value) {
        //         if ($key['inventoryID'] == $value) {
        //             $inventoryInfo[$key['inventoryID']] = [
        //                 'eventID' => $key['eventID'],
        //                 'quantity' => $key['quantity']
        //             ];
        //             if (!in_array($key['eventID'], $eventID)) {
        //                 $eventID[] = $key['eventID'];
        //             }
        //         }
        //     }
        // }


        //Retreive Event Info
        // $reference = app('firebase.firestore')->database()->collection('Events')->documents();
        // $dataList = collect($reference->rows());
        // $data = [];
        // foreach ($dataList as $d) {
        //     $data[] = $d->data();
        // }
        // $eventInfo = [];
        // foreach ($data as $key) {
        //     foreach ($eventID as $k => $value) {
        //         if ($key['EventID'] == $value) {
        //             $eventInfo[$key['EventID']] = [
        //                 'Name' => $key['Name']
        //             ];
        //         }
        //     }
        // }


        $inventoryInfo = $this->database->getReference($this->ref_table_inventories)->getValue();
        $bloodList = [];
        // $inventoryList = [];
        // $eventList = [];
        foreach ($inventoryInfo as $info => $details) {
            foreach ($details['bloodInfo'] as $key => $value) {
                if($value['ShipmentID']== $id){
                    $bloodList[$key] = $value;
                    $inventoryID = ['inventoryID'=>$info];
                    $eventName = ['eventName' => $details['eventID']];
                    $bloodList[$key] = array_merge($bloodList[$key], $inventoryID);
                    $bloodList[$key] = array_merge($bloodList[$key], $eventName);
                    // if(!in_array($info, $inventoryList)){
                    //     $inventoryList[] = $info;
                    // }
                    // if(!in_array($details['eventID'], $eventList)){
                    //     $eventList[] = $details['eventID'];
                    // }
                }
            }
        }

        foreach($bloodList as $key => $value){
            $eventName = $this->database->getReference($this->ref_table_event)->getChild($value['eventName'])->getChild('Name')->getValue();
            $bloodList[$key]['eventName'] = $eventName;

        }
        // dd($listInfo, $inventoryID, $inventoryInfo,$eventID, $eventInfo);

        ksort($bloodList);
        // ksort($inventoryList);
        // ksort($eventList);

        $returnData = [
            'shipmentID' => $id,
            'shipInfo' => $shipmentInfo,
            'bloodList' => $bloodList,
            // 'inventoryList' => $inventoryList,
            // 'eventList' => $eventList
        ];

        return $returnData;
    }

    public function editStatus(Request $request, $id)
    {

        $status = $request->status;
        $childKey = 'Status';
        $this->database->getReference($this->ref_table_shipment)->getChild($id)->update([$childKey=>$status]);
        // $reference = $this->ref_table_firestore->documents();

        // foreach ($reference as $r) {
        //     $rData = $r->data();
        //     if ($rData['ShipID'] === $id) {
        //         $document_Id = $r->reference()->id();
        //         $firestore = $this->ref_table_firestore->document($document_Id);
        //         $firestore->update([
        //             ['path' => 'Status', 'value' => $status]
        //         ]);
        //     }
        // }

        return;

        // $url = 'shipment-view-detials/' . $id;
        // $session_Status = 'Status has been modified to ' . $status;

        // return redirect($url)->with('status', $session_Status);
    }

    public function filterBlood($list, $bloodType_1, $status)
    {
        $info = [];
        foreach ($list as $key => $item) {

            if ($item['bloodType'] === $bloodType_1) {
                if ($item['status'] === $status)
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

    // public function changeStatus($list, $shipmentID)
    // {
    //     foreach ($list as $key => &$i) {
    //         $i['status'] = 'Shipment';
    //         $i['ShipmentID'] = $shipmentID;
    //     }
    //     return $list;
    // }

    public function updateList($list, $shipmentID)
    {
        $updateKey = 'status';
        $updateValue = 'Shipment';

        foreach ($list as $key => $value) {
            $inventoryID = substr($key, 0, 8);
            $this->database->getReference($this->ref_table_inventories)->getChild($inventoryID)->getChild('bloodInfo')->getChild($key)->update([$updateKey => $updateValue, 'ShipmentID' => $shipmentID]);
        }

        // $reference = app("firebase.firestore")->database()->collection("inventoryList")->documents();
        //Upload to inventoryList
        // foreach ($reference as $r) {
        //     $rData = $r->data();
        //     foreach ($rData as $key => $value) {
        //         // Check if the list ID is present in array
        //         if (isset($list[$key])) {
        //             // If the document ID is present 
        //             $document_Id = $r->reference()->id();
        //             $firestore = app('firebase.firestore')->database()->collection('inventoryList')->document($document_Id);
        //             $firestore->update([
        //                 ['path' => $key, 'value' => $list[$key]]
        //             ]);
        //         }
        //     }
        // }
    }
}
