<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class ReportAPIController extends Controller
{
    public function showInventoryReport()
    {
        return $this->getInventoryReport();
    }

    public function getLargestStockIn($numOfRecord)
    {
        $letter = 'I';
        $inventoryInfo = $this->database->getReference($this->ref_table_inventories)->getValue();

        if($inventoryInfo == null){
            
        }


        $inventoryInfo = $this->filterMonth($inventoryInfo, $letter);
        $key = 'quantity';
        return $this->getLargest($inventoryInfo, $this->ref_table_inventories, $key, $numOfRecord);
    }

    public function getLargestStockOut($numOfRecord)
    {
        $letter = 'S';
        $stockOutList = $this->database->getReference($this->ref_table_shipment)->getValue();
        $stockOutList = $this->filterMonth($stockOutList, $letter);
        $key = 'Quantity';
        return $this->getLargest($stockOutList, $this->ref_table_shipment, $key, $numOfRecord);
    }

    public function filterMonth($list, $letter)
    {
        $returnList = [];
        $today = Carbon::now();
        $getMonthCode = substr($today->year, -2) . sprintf("%02s", $today->month);
        foreach ($list as $key => $value) {
            if (substr($key, strlen($letter), 4) == $getMonthCode) {
                $returnList[$key] = $value;
            }
        }
        return $returnList;
    }

    public function getLargest($infos, $collection, $key, $numOfRecord)
    {
        //CALCULATE ALL QUANTITY
        foreach ($infos as $info => $value) {
            $totalNumber = 0;
            foreach ($value[$key] as $number) {
                $totalNumber += $number;
            }
            $list[$info] = ['sumOfQuantity' => $totalNumber];
        }

        //SORT
        uasort($list, function ($a, $b) {
            return $b['sumOfQuantity'] - $a['sumOfQuantity'];
        });

        //GET FIRST 5 RECORD
        $list = array_slice($list, 0, $numOfRecord);

        foreach ($list as $key => $value) {
            $record[$key] = $this->database->getReference($collection)->getChild($key)->getValue();
        }
        return $record;
    }

    public function countBlood_BloodType($list)
    {
        $quantity = [
            'BloodTypeA' => 0,
            'BloodTypeB' => 0,
            'BloodTypeO' => 0,
            'BloodTypeAB' => 0,
        ];
        foreach ($list as $bloodType => $bloodQuantity) {
            switch ($bloodType) {
                case 'aPositive':
                    $quantity['BloodTypeA'] += $bloodQuantity;
                    break;
                case 'aNegative':
                    $quantity['BloodTypeA'] += $bloodQuantity;
                    break;
                case 'bPositive':
                    $quantity['BloodTypeB'] += $bloodQuantity;
                    break;
                case 'bNegative':
                    $quantity['BloodTypeB'] += $bloodQuantity;
                    break;
                case 'oPositive':
                    $quantity['BloodTypeO'] += $bloodQuantity;
                    break;
                case 'oNegative':
                    $quantity['BloodTypeO'] += $bloodQuantity;
                    break;
                case 'abPositive':
                    $quantity['BloodTypeAB'] += $bloodQuantity;
                    break;
                case 'abNegative':
                    $quantity['BloodTypeAB'] += $bloodQuantity;
                    break;
            }
        }
        return $quantity;
    }

    public function countBlood_Status($list)
    {
        $total = ['Shipment' => 0, 'Available' => 0];
        foreach ($list as $bloodID => $bloodInfo) {
            if ($bloodInfo['status'] == 'Shipment') {
                $total['Shipment']++;
            }
            if ($bloodInfo['status'] == 'Available') {
                $total['Available']++;
            }
        }
        return $total;
    }

    public function getInventoryReport()
    {
        $numOfRecord = 3;
        $today = Carbon::now();
        $month = $today->format('F');

        $stockInList = $this->getLargestStockIn($numOfRecord);
        $stockOutList = $this->getLargestStockOut($numOfRecord);
        // dd($stockInList, $stockOutList);

        //ADD EVENT NAME INTO THE ARRAY
        foreach ($stockInList as $key => $item) {
            $data = $this->database->getReference($this->ref_table_event)->getChild($item['eventID'])->getChild('eventName')->getValue();
            $stockInList[$key]['EventName'] = $data;
        }

        //CHANGE SOTCK IN LIST QUANTITY
        foreach ($stockInList as $key => $value) {
            $quantity = $this->countBlood_BloodType($value['quantity']);
            $stockInList[$key]['quantity'] = $quantity;
        }

        //COUNT PARTICULAR STATUS
        foreach ($stockInList as $key => $value) {
            $total = $this->countBlood_Status($value['bloodInfo']);
            $stockInList[$key]['StatusQuantity'] = $total;
        }

        //SUMMARY

        // TOTAL NUMBER OF BLOOD RECEIVE
        $receiveList = $this->filterMonth($this->database->getReference($this->ref_table_inventories)->getValue(), 'I');
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
        $numOfBlood_available = [
            'aPositive' => 0,
            'aNegative' => 0,
            'bPositive' => 0,
            'bNegative' => 0,
            'oPositive' => 0,
            'oNegative' => 0,
            'abPositive' => 0,
            'abNegative' => 0
        ];
        foreach ($receiveList as $key => $value) {
            foreach ($value['quantity'] as $bloodType => $quantity) {
                switch ($bloodType) {
                    case 'aPositive':
                        $numOfBlood['aPositive'] += $quantity;
                        break;
                    case 'aNegative':
                        $numOfBlood['aNegative'] += $quantity;
                        break;
                    case 'bPositive':
                        $numOfBlood['bPositive'] += $quantity;
                        break;
                    case 'bNegative':
                        $numOfBlood['bNegative'] += $quantity;
                        break;
                    case 'oPositive':
                        $numOfBlood['oPositive'] += $quantity;
                        break;
                    case 'oNegative':
                        $numOfBlood['oNegative'] += $quantity;
                        break;
                    case 'abPositive':
                        $numOfBlood['abPositive'] += $quantity;
                        break;
                    case 'abNegative':
                        $numOfBlood['abNegative'] += $quantity;
                        break;
                }
            }

            foreach ($value['bloodInfo'] as $bloodID => $info) {

                if ($info['status'] == 'Available') {
                    switch ($info['bloodType']) {
                        case 'aPositive':
                            $numOfBlood_available['aPositive']++;
                            break;
                        case 'aNegative':
                            $numOfBlood_available['aNegative']++;
                            break;
                        case 'bPositive':
                            $numOfBlood_available['bPositive']++;
                            break;
                        case 'bNegative':
                            $numOfBlood_available['bNegative']++;
                            break;
                        case 'oPositive':
                            $numOfBlood_available['oPositive']++;
                            break;
                        case 'oNegative':
                            $numOfBlood_available['oNegative']++;
                            break;
                        case 'abPositive':
                            $numOfBlood_available['abPositive']++;
                            break;
                        case 'abNegative':
                            $numOfBlood_available['abNegative']++;
                            break;
                    }
                }
            }
        }
        //CHANGE SOTCK OUT LIST QUANTITY
        foreach ($stockOutList as $key => $value) {
            $quantity = $this->countBlood_BloodType($value['Quantity']);
            $stockOutList[$key]['Quantity'] = $quantity;
        }

        $numOfBlood_Shipped = [
            'aPositive' => 0,
            'aNegative' => 0,
            'bPositive' => 0,
            'bNegative' => 0,
            'oPositive' => 0,
            'oNegative' => 0,
            'abPositive' => 0,
            'abNegative' => 0
        ];
        $shippedList = $this->database->getReference($this->ref_table_shipment)->getValue();
        foreach ($shippedList as $key => $value) {
            foreach ($value['Quantity'] as $bloodType => $quantity) {
                switch ($bloodType) {
                    case 'aPositive':
                        $numOfBlood_Shipped['aPositive'] += $quantity;
                        break;
                    case 'aNegative':
                        $numOfBlood_Shipped['aNegative'] += $quantity;
                        break;
                    case 'bPositive':
                        $numOfBlood_Shipped['bPositive'] += $quantity;
                        break;
                    case 'bNegative':
                        $numOfBlood_Shipped['bNegative'] += $quantity;
                        break;
                    case 'oPositive':
                        $numOfBlood_Shipped['oPositive'] += $quantity;
                        break;
                    case 'oNegative':
                        $numOfBlood_Shipped['oNegative'] += $quantity;
                        break;
                    case 'abPositive':
                        $numOfBlood_Shipped['abPositive'] += $quantity;
                        break;
                    case 'abNegative':
                        $numOfBlood_Shipped['abNegative'] += $quantity;
                        break;
                }
            }
        }

        $data = [
            'month' => $month,
            'stockInList' => $stockInList,
            'numOfBlood' => $numOfBlood,
            'numOfBlood_available' => $numOfBlood_available,
            'stockOutList' => $stockOutList,
            'numOfBlood_Shipped' => $numOfBlood_Shipped
        ];
        return $data;
    }

    public function downloadPDF(Request $request)
    {
        // Retrieve data for the PDF report
        $data = $this->getInventoryReport();

        // Get the current year and month
        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        // Define the file name
        $fileName = 'InventoryReport-' . substr($year, -2) . sprintf("%02s", $month) . '.pdf';

        // Load the view and get the HTML content
        $pdf = PDF::loadView('BackEnd.Report.inventoryReport', $data);
        return $pdf->download($fileName.'.pdf');
    }
    

}
