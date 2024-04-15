<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleBaseController extends Controller
{

    public function index()
    {

        if (!$this->isRootUser()) {
            return view('BackEnd.JenSien.permissionDenied');
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

        return view('BackEnd.JenSien.rbacList')->with('userList', $userList);
    }

    public function getPermission($userKey)
    {
        $inventoryControl = [
            'read' => 'off',
            'stockIn' => 'off',
            'stockEdit' => 'off',
            'stockOut' => 'off'
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

        $inventoryControl = [
            'read' => $req->read_inventory,
            'stockIn' => $req->stockIn_inventory,
            'stockEdit' => $req->stockEdit_inventory,
            'stockOut' => $req->stockOut_inventory,
        ];
        $shipmentControl = [
            'view_shipment' => $req->view_shipment,
            'update_shipment' => $req->update_shipment
        ];
        $eventControl = [
            'add_event' => $req->add_event,
            'edit_event' => $req->edit_event,
            'delete_event' => $req->delete_event,
        ];

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
        return redirect('role-base-control')->with('status', $status);
    }

    public function nullToOff($list)
    {
        foreach ($list as $key => $value) {
            if ($value == null)
                $list[$key] = 'off';
        }
        return $list;
    }

    public function isRootUser()
    {
        // Root User Email: wjsadmin@gmail.com
        // Root User Name : ADMIN;

        if (session('user.key') == $this->rootUser) {
            return true;
        } else {
            return false;
        }
    }
}
