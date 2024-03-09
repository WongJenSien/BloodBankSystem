<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function checkLogin(){
        if (session()->has('user')) {
            return true;
        } else {
            return false;
        }
    }

    function uploadFile($file, $path)
    {
        $result = ['name' => '', 'path' => ''];

        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameSave = session('user.key') . '_' . time() . '.' . $extension;
        if ($file->storeAs($path, $fileNameSave)) {
            $result['name'] = $fileName;
            $result['path'] = $fileNameSave;
        }

        return $result;
    }
}
