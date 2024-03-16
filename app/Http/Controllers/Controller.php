<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $database;
    protected $ref_table_shipment;
    protected $ref_table_inventories;
    protected $ref_table_hospital;
    protected $ref_table_event;
    protected $ref_table_user;
    protected $ref_table_appointment;
    protected $ref_table_rbac;
    protected $defaultPermission;
    protected $rootUser;
    protected $MIN_QUANTITY;

    public function __construct(Database $database)
    {
        
        $this->ref_table_shipment = "Shipment";
        $this->ref_table_inventories = "Inventories";
        $this->ref_table_event = 'Events';
        $this->ref_table_hospital = "Hospital";
        $this->ref_table_user = 'Users';
        $this->ref_table_appointment = "Appointment";
        $this->ref_table_rbac = 'RBAC';
        $this->rootUser = '-NsXQSWtBI70olEQRvQ-';

        $inventoryControl = [
            'read' => 'on',
            'stockIn' => 'off',
            'stockOut' => 'off'
        ];
        $shipmentControl = [
            'update_shipment' => 'off',
            'view_shipment' => 'on',
        ];
        $eventControl = [
            'add_event' => 'on',
            'edit_event' => 'on',
            'delete_event' => 'off'
        ];
        $this->defaultPermission = [
            'inventoryControl' => $inventoryControl,
            'shipmentControl' => $shipmentControl,
            'eventControl' => $eventControl,
            'userID' => ''
        ];

        $this->database = $database;
    }
    function checkLogin()
    {
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

    function idGenerator($letter, $ref_table)
    {
        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        $lastID = $this->database->getReference($ref_table)->orderByKey()->limitToLast(1)->getValue();
        if ($lastID != null) {
            $lastID = array_keys($lastID)[0];
        }

        //if no last record
        if ($lastID === null || substr($lastID, strlen($letter), 4) != substr($year, -2) . sprintf("%02s", $month)) {
            $newID = $letter . substr($year, -2) . sprintf("%02s", $month) . "001";
        } else {
            $newID = $lastID;
            $last = substr($newID, -3);
            $newNum = intval($last) + 1;

            $newID = $letter . substr($year, -2) . sprintf("%02s", $month) . sprintf("%03d", $newNum);
        }
        return $newID;
    }

    public function assignDefaultPermission($key)
    {
        $user = $this->database->getReference($this->ref_table_user)->orderByChild('emailAddress')->equalTo($key)->getValue();
        $this->defaultPermission['userID'] = key($user);
        $this->database->getReference($this->ref_table_rbac)->push($this->defaultPermission);
    }

    public function verifyPermission()
    {
        $url = $this->getURL();
        $userKey = session('user.key');
        
        if($userKey == null){
            return false;
        }
        $url = $this->removeUrlProtocol($url);
        $permission = $this->database->getReference($this->ref_table_rbac)->orderByChild('userID')->equalTo($userKey)->getValue();
        foreach ($permission as $key => $value) {
            $inventoryControl = $value['inventoryControl'];
            $shipmentControl = $value['shipmentControl'];
            $eventControl = $value['eventControl'];
        }

        switch ($url) {
                //INVENTORY CONTROL
            case 'view-inventory':
                return $inventoryControl['read'] == 'on';
            case 'add-inventory':
                return $inventoryControl['stockIn'] == 'on';
            case 'remove-inventory':
                return $inventoryControl['stockOut'] == 'on';

                //SHIPMENT CONTROL
            case 'view-shipment':
                return $shipmentControl['view_shipment'] == 'on';
            case 'shipment-view-detials':
                return $shipmentControl['view_shipment'] == 'on';
            case 'shipment-edit-status':
                return $shipmentControl['update_shipment'] == 'on';

                //EVENT CONTROL
            case 'addEvent':
                return $eventControl['add_event'] == 'on';
            case 'updateEvent':
                return $eventControl['edit_event'] == 'on';
            case 'deleteEvent':
                return $eventControl['delete_event'] == 'on';

                case 'role-base-control':
                    return $this->rootUser();
        }
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
            case 'stockOut.php':
                return $inventoryControl['stockOut'] == 'on';

                //SHIPMENT CONTROL
            case 'shipmentView.php':
                return $shipmentControl['view_shipment'] == 'on';
            case 'shipmentViewDetails.php':
                return $shipmentControl['update_shipment'] == 'on';
        }
    }
    public function removeUrlProtocol($url)
    {
        $currentUrl = $url;
        // Parse the URL
        $parsedUrl = parse_url($currentUrl);

        // Extract the path
        $path = $parsedUrl['path'];

        // Remove leading and trailing slashes
        $path = trim($path, '/');

        // Extract the route
        $segments = explode('/', $path);
        $route = reset($segments); // Get the first segment
        return $route;
    }

    public function getURLParameter($url){
        $currentUrl = $url;
        // Parse the URL
        $parsedUrl = parse_url($currentUrl);

        // Extract the path
        $path = $parsedUrl['path'];

        // Remove leading and trailing slashes
        $path = trim($path, '/');

        // Extract the route
        $segments = explode('/', $path);
        $parameters = array_slice($segments, 1);
        return $parameters;
    }

    public function getURL()
    {
        return  URL::current();
    }
}
