<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;


class ShipmentController extends Controller
{

    public function store(Request $request)
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }
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

        //Status of Shipment: Pending, Delivering, Shipped
        $postData = [
            "Quantity" => $quantity,
            "RequestDate" => $requestDate,
            "location" => $location,
            "ShipDate" => $shipmentDate,
            "Description" => $description,
            "Status" => $status
        ];


        $data = $this->database->getReference($this->ref_table_inventories)->getValue();

        foreach ($data as $key => $value) {
            foreach ($value['bloodInfo'] as $bKey => $bValue)
                $listInfo[$bKey] = $bValue;
        }


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

        //save shipment
        $postRef = $this->database->getReference($this->ref_table_shipment . '/' . $shipmentID)->set($postData);
        if ($postRef) {
            return redirect('view-shipment')->with('status', 'Added Successfully');
        } else {
            return redirect('view-shipment')->with('status', 'Added Failed');
        }
    }

    public function index(Request $request)
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }

        $info[] = $this->database->getReference($this->ref_table_shipment)->getValue();
        $info = reset($info);
        return view('BackEnd.JenSien.viewShipment')
            ->with('shipInfo', $info);
    }

    public function show($id)
    {
        if (!$this->verifyPermission()) {
            return view('BackEnd.JenSien.permissionDenied');
        }

        //Retreive Shipment Infomation
        $shipmentInfo = $this->database->getReference($this->ref_table_shipment)->getChild($id)->getValue();
        $inventoryInfo = $this->database->getReference($this->ref_table_inventories)->getValue();

        //select the inventory bloodinfo that belong to this shipment
        $bloodList = [];

        foreach ($inventoryInfo as $info => $details) {
            foreach ($details['bloodInfo'] as $key => $value) {
                if ($value['ShipmentID'] == $id) {
                    $bloodList[$key] = $value;
                    $inventoryID = ['inventoryID' => $info];
                    $eventName = ['eventName' => $details['eventID']];
                    $bloodList[$key] = array_merge($bloodList[$key], $inventoryID);
                    $bloodList[$key] = array_merge($bloodList[$key], $eventName);
                }
            }
        }

        foreach ($bloodList as $key => $value) {

            $eventName = $this->database->getReference($this->ref_table_event)->getChild($value['eventName'])->getChild('eventName')->getValue();
            $bloodList[$key]['eventName'] = $eventName;
        }
        ksort($bloodList);

        return view('BackEnd.JenSien.viewShipmentDetails')
            ->with('shipmentID', $id)
            ->with('shipInfo', $shipmentInfo)
            ->with('bloodList', $bloodList);
    }

    public function editStatus(Request $request)
    {
        if(!$this->verifyPermission()){
            return view('BackEnd.JenSien.permissionDenied');
        }
        $childKey = 'Status';
        $id = $request->shipID;
        $status = $request->status;
        $this->database->getReference($this->ref_table_shipment)->getChild($id)->update([$childKey => $status]);


        $url = 'shipment-view-detials/' . $id;
        $session_Status = 'Status has been modified to ' . $status;
        return redirect($url)->with('status', $session_Status);
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


    public function updateList($list, $shipmentID)
    {
        if(!$this->verifyPermission()){
            return view('BackEnd.JenSien.permissionDenied');
        }
        $updateKey = 'status';
        $updateValue = 'Shipment';

        foreach ($list as $key => $value) {
            $inventoryID = substr($key, 0, 8);
            $this->database->getReference($this->ref_table_inventories)->getChild($inventoryID)->getChild('bloodInfo')->getChild($key)->update([$updateKey => $updateValue, 'ShipmentID' => $shipmentID]);
        }
    }
}
