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
        $this->ref_table_firestore = app('firebase.firestore')->database()->collection('Shipment');
        $this->ref_table = "Shipment";
        $this->database = $database;
    }

    public function store(Request $request)
    {
        $quantity = [
            'aPositive' => $request->aPositive,
            'aNegative' => $request->aNegative,

            'bPositive' => $request->bPositive,
            'bNegative' => $request->bNegative,

            'oPositive' => $request->oPositive,
            'oNegative' => $request->oNegative,

            'abPositive' => $request->abPositive,
            'abNegative' => $request->abNegative,
        ];

        $shipmentID = $request->ship_id;
        $requestDate = $request->ship_today;
        $location = $request->location;
        $shipmentDate = $request->ship_date;
        $description = $request->description;
        $status = 'Pending';
        //Status of Shipment: Pending, Arrive
        $postData = [
            "Quantity" => $quantity,
            "ShipID" => $shipmentID,
            "RequestDate" => $requestDate,
            "location" => $location,
            "ShipDate" => $shipmentDate,
            "Description" => $description,
            "Status" => $status
        ];

        //retrive data rows for the particular blood
        $reference = app('firebase.firestore')->database()->collection('inventoryList')->documents();
        $inventoryListData = collect($reference->rows());
        $packInfo = [];
        foreach ($inventoryListData as $item) {
            $test = $item->data();
            $packInfo[] = $test;
        }
        //FETCH ALL ROW DATA
        $listInfo = [];
        foreach ($packInfo as $key => $value) {

            foreach ($value as $item => $item2) {
                $listInfo[$item] = $item2;
            }
        }

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

        if ($postRef) {
            return redirect('view-shipment')->with('status', 'Added Successfully');
        } else {
            return redirect('view-shipment')->with('status', 'Added Failed');
        }
    }

    public function index(Request $request)
    {
        $reference = $this->ref_table_firestore->documents();
        $data = collect($reference->rows());
        $info = [];
        foreach ($data as $d) {
            $info[] = $d->data();
        }
        return view('BackEnd.JenSien.viewShipment')
            ->with('shipInfo', $info);
    }

    public function show($id)
    {
        //Retreive Shipment Infomation
        $reference = app('firebase.firestore')->database()->collection('Shipment')->documents();
        $dataList = collect($reference->rows());
        foreach($dataList as $d){
            $test = $d->data();
            if($d->data()['ShipID'] == $id){
                $shipmentInfo = $d->data();
            }

        }


        //Retreive All Data FROM INVENTORY LIST
        $reference = app('firebase.firestore')->database()->collection('inventoryList')->documents();
        $dataList = collect($reference->rows());

        //FilterBlood
        $inventoryID = [];
        foreach ($dataList as $d) {
            foreach ($d->data() as $key => $value)
                if ($value['ShipmentID'] !== null && $value['ShipmentID'] === $id) {
                    $listInfo[$key] = $value;
                    if (!in_array($value['inventoryID'], $inventoryID)) {
                        $inventoryID[] = $value['inventoryID'];
                    }
                }
        }
   


        //Retreive inventory info
        $reference = app('firebase.firestore')->database()->collection('Inventories')->documents();
        $dataList = collect($reference->rows());

        $data = [];
        foreach ($dataList as $d) {
            $data[] = $d->data();
        }
$eventID = [];
        foreach ($data as $key) {
            foreach ($inventoryID as $k => $value) {
                if ($key['inventoryID'] == $value) {
                    $inventoryInfo[$key['inventoryID']] = [
                        'eventID' => $key['eventID'],
                        'quantity' => $key['quantity']
                    ];
                    if (!in_array($key['eventID'], $eventID)) {
                        $eventID[] = $key['eventID'];
                    }
                }
            }
        }


        //Retreive Event Info
        $reference = app('firebase.firestore')->database()->collection('Events')->documents();
        $dataList = collect($reference->rows());
        $data = [];
        foreach ($dataList as $d) {
            $data[] = $d->data();
        }
        $eventInfo = [];
        foreach ($data as $key) {
            foreach ($eventID as $k => $value) {
                if ($key['EventID'] == $value) {
                    $eventInfo[$key['EventID']] = [
                        'Name' => $key['Name']
                    ];
                }
            }
        }

        // dd($listInfo, $inventoryID, $inventoryInfo,$eventID, $eventInfo);

        ksort($shipmentInfo['Quantity']);
        ksort($listInfo);
        ksort($inventoryInfo);
        ksort($eventInfo);

        return view('BackEnd.JenSien.viewShipmentDetails')
            ->with('shipInfo', $shipmentInfo)
            ->with('listInfo', $listInfo)
            ->with('inventoryInfo', $inventoryInfo)
            ->with('eventInfo', $eventInfo);
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

    public function getInventoryList()
    {
    }
}
