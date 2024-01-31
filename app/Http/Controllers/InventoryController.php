<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class InventoryController extends Controller
{
    protected $database;
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

public function shipOut(){

    return view('BackEnd.JenSien.stockOut');
}

    public function create()
    {
        return view("BackEnd.JenSien.stockIn");
    }

    public function store(Request $request)
    {
        $ref_tablename = 'Users';

        $postData = [
            'name' => $request->name,
            'password' => $request->password
        ];

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

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
