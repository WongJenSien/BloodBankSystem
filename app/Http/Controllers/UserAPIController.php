<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAPIController extends Controller
{
    //
    public function login(Request $request)
    {

        $email = $request->email;
        $password = $request->password;

        $userList = $this->database->getReference($this->ref_table_user)->getValue();

        foreach ($userList as $key => $value) {
            
            if ($value['emailAddress'] == $email && Hash::check($password, $value['password'])) {
                return [$key];
            }
        }
        return $email;
    }

    public function show($id){
        return $this->database->getReference($this->ref_table_user)->getChild($id)->getValue();
        
    }

}
