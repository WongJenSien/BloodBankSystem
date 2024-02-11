<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class InventoryController extends Controller
{
    protected $database;
    protected $ref_table;
    public function __construct(Database $database)
    {
        $this->ref_table = "Inventories";
        $this->database = $database;
    }

    public function shipOut()
    {

        return view('BackEnd.JenSien.stockOut');
    }

    public function create()
    {
        return view("BackEnd.JenSien.stockIn");
    }

    public function store(Request $request)
    {
        $inventoryID = $request->inventoryID;
        $bloodType = array('aPositive', 'aNegative', 'bPositive', 'bNegative', 'oPositive', 'oNegative', 'abPositive', 'abNegative');
        $expirationDate = array(
            'exDate_A_P' => $request->expiredDate_A_P,
            'exDate_A_N' => $request->expiredDate_A_N,

            'exDate_B_P' => $request->expiredDate_B_P,
            'exDate_B_N' => $request->expiredDate_B_N,

            'exDate_O_P' => $request->expiredDate_O_P,
            'exDate_O_N' => $request->expiredDate_O_N,

            'exDate_AB_P' => $request->expiredDate_AB_P,
            'exDate_AB_N' => $request->expiredDate_AB_N
        );

        $status = "Available"; //By Default
        $quantity = array(
            'aPositive' => $request->aPositive,
            'aNegative' => $request->aNegative,

            'bPositive' => $request->bPositive,
            'bNegative' => $request->bNegative,

            'oPositive' => $request->oPositive,
            'oNegative' => $request->oNegative,

            'abPositive' => $request->abPositive,
            'abNegative' => $request->abNegative,
        );

        $eventID = $request->eventID;

        // for ($i = 0; $i < count($bloodType); $i++) {
        //     $postData = [
        //         'inventoryID' => $inventoryID,
        //         'expirationDate' => next($expirationDate),
        //         'status' => $status,
        //         'quantity' => next($quantity),
        //         'bloodType' => next($bloodType),
        //         'eventID' => $eventID
        //     ];
        // }

        $postData = [
            'inventoryID' => $inventoryID,
            'expirationDate' => $expirationDate,
            'status' => $status,
            'quantity' => $quantity,
            'bloodType' => $bloodType,
            'eventID' => $eventID
        ];

        $postRef = $this->database->getReference($this->ref_table)->push($postData);

        if($postRef){
            return redirect('view-inventory')->with('status', 'Added Successfully');
        }else{
            return redirect('view-inventory')->with('status', 'Added Failed');
        }


    }

    public function show(Request $request)
    {
        $reference = $this->database->getReference('Users')->getValue();
        return view('BackEnd.JenSien.viewStock', compact('reference'));
    }

    public function edit(Request $request)
    {
    }
    public function update(Request $request)
    {
    }
    public function destroy(Request $request)
    {
    }
    public function restore(Request $request)
    {
    }
}
