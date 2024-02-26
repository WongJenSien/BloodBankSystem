<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class InventoryAPIController extends Controller
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

    public function index(){
        // $reference = $this->ref_table_firestore_inventories->documents();
        // $data = collect($reference->rows());
        // $info = [];
        // foreach($data as $d){
        //     $info[] = $d->data();
        // }
        // return $info;


        $reference = $this->ref_table_firestore_inventories->documents();
        $data = collect($reference->rows());

        $reference = $this->ref_table_firestore_inventoriesList->documents();
        $list = collect($reference->rows());

        $temp = [];
        foreach ($list as $item) {
            $temp[] = $item->data();
        }
        $listInfo = [];
        foreach($temp as $key => $value){
            foreach($value as $item => $item2){
                $listInfo[$item] = $item2;
            }
        }

        //FILTER BLOOD TYPE
        $infoA = $this->filterBlood($listInfo, 'AP', 'AN');
        $infoB = $this->filterBlood($listInfo, 'BP', 'BN');
        $infoO = $this->filterBlood($listInfo, 'OP', 'ON');
        $infoAB = $this->filterBlood($listInfo, 'ABP', 'ABN');

        $package_info = [
            'infoA' => $infoA, 
            'infoB' => $infoB, 
            'infoO' => $infoO, 
            'infoAB' => $infoAB];

        //COUNT BLOOD STATUS
        $status_info_A = $this->countBlood($listInfo, 'aPositive', 'aNegative');
        $status_info_B = $this->countBlood($listInfo, 'bPositive', 'bNegative');
        $status_info_O = $this->countBlood($listInfo, 'oPositive', 'oNegative');
        $status_info_AB = $this->countBlood($listInfo, 'abPositive', 'abNegative');

        $package_status_info = [
            'status_info_A' => $status_info_A, 
            'status_info_B' => $status_info_B, 
            'status_info_O' => $status_info_O, 
            'status_info_AB' => $status_info_AB];

        $numOfBlood = $this->getNumOfBlood($data);
        $totalNumOfBlood = $this->getTotalNumOfBlood($numOfBlood);

        $returnData = [
            "numOfBlood" => $numOfBlood,
            "totalNumOfBlood" => $totalNumOfBlood,
            "package_info" => $package_info,
            "package_status_info" => $package_status_info
        ];

        return $returnData;
    }

    public function store(Request $req){}
    public function show($id){}
    public function update(Request $req, $id){}
    public function destroy($id){}


    public function filterBlood($list, $bloodType_1, $bloodType_2){
        $info = [];
        foreach ($list as $key => $item) {
            if (strpos($key, $bloodType_1) !== false || strpos($key, $bloodType_2) !== false) {
                $info[$key] = $item;
            }
        }
        krsort($info);
        return $info;
    }

    public function countBlood($list, $bloodType_1, $bloodType_2){
        $info = [
            'Available_P'=> 0, 
            'Available_N'=> 0, 
            'Shipment_P'=> 0, 
            'Shipment_N'=> 0, 
        ];

        foreach($list as $key => $item){
            if($item['status'] === 'Available' && $item['bloodType']=== $bloodType_1){
                $info['Available_P']++;
            }
            if($item['status'] === 'Available' && $item['bloodType']=== $bloodType_2){
                $info['Available_N']++;
            }
            
            if($item['status'] === 'Shipment' && $item['bloodType']=== $bloodType_1){
                $info['Shipment_P']++;
            }
            if($item['status'] === 'Shipment' && $item['bloodType']=== $bloodType_2){
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
}
