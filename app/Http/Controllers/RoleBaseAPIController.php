<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleBaseAPIController extends Controller
{

    public function index(Request $req)
    {
        if ($req->userKey != $this->rootUser) {
            return [false];
        }

        $userList = $this->database->getReference($this->ref_table_user)->getValue();

        //REMOVE THE NORMAL USER FROM THE LIST
        foreach ($userList as $key => $item) {
            if ($item['roleID'] != 1) {
                unset($userList[$key]);
            }
        }

        foreach ($userList as $key => $item) {
            $userList[$key]['permission'] = $this->getPermission($key);
        }

        return $userList;
    }

    public function getPermission($userKey)
    {
        $inventoryControl = [
            'read' => 'off',
            'stockIn' => 'off',
            'stockEdit' => 'off',
            'stockOut' => 'off',
        ];
        $shipmentControl = [
            'update_shipment' => 'off',
            'view_shipment' => 'off',
        ];
        $eventControl = [
            'add_event' => 'off',
            'edit_event' => 'off',
            'delete_event' => 'off'
        ];
        $permission = $this->database->getReference($this->ref_table_rbac)->getValue();
        if ($permission != null) {
            foreach ($permission as $key => $item) {
                if ($item['userID'] == $userKey) {
                    $inventoryControl = $item['inventoryControl'];
                    $shipmentControl = $item['shipmentControl'];
                    $eventControl = $item['eventControl'];
                }
            }
        }

        return [
            'inventoryControl' => $inventoryControl,
            'shipmentControl' => $shipmentControl,
            'eventControl' => $eventControl,
        ];
    }

    public function editPermission(Request $req)
    {
        $userKey = $req->userKey;

        $inventoryControl = $req->inventoryControl;
        $shipmentControl = $req->shipmentControl;
        $eventControl = $req->eventControl;

        $permissionKey = null;
        //FOUND IF the USER IS EXIST
        $permissionList = $this->database->getReference($this->ref_table_rbac)->getValue();
        if ($permissionList != null) {
            foreach ($permissionList as $key => $item) {
                if ($item['userID'] == $userKey) {
                    $permissionKey = $key;
                }
            }
        }

        //change the permission null to off
        $inventoryControl = $this->nullToOff($inventoryControl);
        $shipmentControl = $this->nullToOff($shipmentControl);
        $eventControl = $this->nullToOff($eventControl);

        $postData = [
            'userID' => $userKey,
            'inventoryControl' => $inventoryControl,
            'shipmentControl' => $shipmentControl,
            'eventControl' => $eventControl
        ];
        if ($permissionKey != null) {
            $this->database->getReference($this->ref_table_rbac . '/' . $permissionKey)->set($postData);
        } else {
            $this->database->getReference($this->ref_table_rbac)->push($postData);
        }

        $status = 'Permission Edited';
        return $postData;
    }

    public function nullToOff($list)
    {
        foreach ($list as $key => $value) {
            if ($value == null)
                $list[$key] = 'off';
        }
        return $list;
    }

    public function validatePermission(Request $req)
    {
        if($req->userKey == null){
            return ['index'];
        }

        if (!$this->verifyAPIPermission($req->currentURL, $req->userKey)) {
            return [false];
        }
        return [true];
    }
    
    public function verifyAPIPermission($url, $userKey)
    {
        $url = basename($url);

        $permission = $this->database->getReference($this->ref_table_rbac)->orderByChild('userID')->equalTo($userKey)->getValue();

        foreach ($permission as $key => $value) {
            $inventoryControl = $value['inventoryControl'];
            $shipmentControl = $value['shipmentControl'];
            $eventControl = $value['eventControl'];
        }

        switch ($url) {
                //INVENTORY CONTROL
            case 'stockView.php':
                return $inventoryControl['read'] == 'on';
            case 'stockIn.php':
                return $inventoryControl['stockIn'] == 'on';
            case 'stockEdit.php':
                return $inventoryControl['stockEdit'] == 'on';
            case 'stockOut.php':
                return $inventoryControl['stockOut'] == 'on';
                
                //SHIPMENT CONTROL
            case 'shipmentView.php':
                return $shipmentControl['view_shipment'] == 'on';
            case 'shipmentViewDetails.php':
                return $shipmentControl['update_shipment'] == 'on';
            default:
                return 'on';
        }
    }

    public function isRootUser(){
        // Root User Email: wjsadmin@gmail.com
        // Root User Name : ADMIN;
        
        if(session('user.key') == $this->rootUser){
            return true;
        }else{
            return false;
        }
    }
}
