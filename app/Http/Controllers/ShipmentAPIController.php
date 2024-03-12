<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ShipmentAPIController extends Controller
{
    public function index(Request $request)
    {
        $info[] = $this->database->getReference($this->ref_table_shipment)->getValue();
        $info = reset($info);

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
        $this->database->getReference($this->ref_table_shipment . '/' . $shipmentID)->set($postData);

        return;
    }

    public function show($id)
    {
        //Retreive Shipment Infomation
        $shipmentInfo = $this->database->getReference($this->ref_table_shipment)->getChild($id)->getValue();

        $inventoryInfo = $this->database->getReference($this->ref_table_inventories)->getValue();
        $bloodList = [];

        foreach ($inventoryInfo as $info => $details) {
            foreach ($details['bloodInfo'] as $key => $value) {
                if($value['ShipmentID']== $id){
                    $bloodList[$key] = $value;
                    $inventoryID = ['inventoryID'=>$info];
                    $eventName = ['eventName' => $details['eventID']];
                    $bloodList[$key] = array_merge($bloodList[$key], $inventoryID);
                    $bloodList[$key] = array_merge($bloodList[$key], $eventName);
                }
            }
        }

        foreach($bloodList as $key => $value){
            $eventName = $this->database->getReference($this->ref_table_event)->getChild($value['eventName'])->getChild('Name')->getValue();
            $bloodList[$key]['eventName'] = $eventName;

        }
      

        ksort($bloodList);


        $returnData = [
            'shipmentID' => $id,
            'shipInfo' => $shipmentInfo,
            'bloodList' => $bloodList,
        ];

        return $returnData;
    }

    public function editStatus(Request $request, $id)
    {
        $status = $request->status;
        $childKey = 'Status';
        $this->database->getReference($this->ref_table_shipment)->getChild($id)->update([$childKey=>$status]);
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
        $updateKey = 'status';
        $updateValue = 'Shipment';

        foreach ($list as $key => $value) {
            $inventoryID = substr($key, 0, 8);
            $this->database->getReference($this->ref_table_inventories)->getChild($inventoryID)->getChild('bloodInfo')->getChild($key)->update([$updateKey => $updateValue, 'ShipmentID' => $shipmentID]);
        }
    }
}
