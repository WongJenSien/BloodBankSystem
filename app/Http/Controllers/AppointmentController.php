<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Dompdf\Options;
use Dompdf\Dompdf;

class AppointmentController extends Controller
{
    public function index()
    {
        $record = null;
        $record = $this->database->getReference($this->ref_table_appointment)->getValue();
        if ($record != null) {
            foreach ($record as $key => $item) {
                $record[$key]['userName'] = $this->database->getReference($this->ref_table_user)->getChild($item['userID'])->getChild('name')->getValue();
            }
        }
        return view('BackEnd.JenSien.appointmentList')->with('record', $record);
    }
    public function show($id)
    {
        $appList = $this->database->getReference($this->ref_table_appointment)->getValue();
        $record = null;
        $status = null;
        if ($appList != null) {
            foreach ($appList as $key => $item) {
                if ($item['userID'] == $id) {
                    $record = $appList[$key];
                    $record['appID'] = $key;
                    $status = $item['status'];
                }
            }
        }

        if ($record != null) {
            // return redirect('make-appointment');
            $qrCodeData = "Location: newBbi\nFileName";
            $location = $this->database->getReference($this->ref_table_hospital)->getChild($record['location'])->getChild('Name')->getValue();
            $record['location'] = $location;
            $userInfo = $this->database->getReference($this->ref_table_user)->getChild($record['userID'])->getValue();
            $record['userName'] = $userInfo['name'];
            $record['userIc'] = $userInfo['identityCard'];
            $record['qrCode'] = $this->generateQrCode($record);
        }
        // dd($record);
        return view('FrontEnd.Home.viewCertificate')
            ->with('record', $record)
            ->with('status', $status);
    }

    public function destroy($id)
    {
        $this->database->getReference($this->ref_table_appointment)->getChild($id)->remove();
        return redirect('profile');
    }

    public function editResult(Request $req)
    {
        $childKey = 'result';
        $chilKey2 = 'status';
        $status = 'Done';
        $appointmentID = $req->appID;
        $bloodType = $req->bloodType;
        $bloodResult = $req->input('result' . $appointmentID);;

        $result = [
            'bloodType' => $bloodType,
            'testResult' => $bloodResult,
            'testDate' => date('d-M-Y')
        ];
        $this->database->getReference($this->ref_table_appointment)->getChild($appointmentID)->update([$childKey => $result, $chilKey2 => $status]);
        return redirect('appointment-list');
    }

    public function create()
    {
        $data = $this->database->getReference($this->ref_table_hospital)->getValue();

        return view('FrontEnd.Home.appointment')->with('data', $data);
    }

    public function store(Request $req)
    {
        $status = 'Pending';
        $result = [
            'bloodType' => '',
            'testResult' => '',
            'testDate' => ''
        ];
        $location = $req->hospitalID;
        $currentUser = session('user.key');
        $pre_date = $req->preferred_date;
        $pre_time = $req->preferred_time;


        // Validate All information
        $err = $this->getErrMessage($location, $currentUser, $pre_date, $pre_time);
        if (!empty($err)) {
            if (!$this->validHospital($location))
                return redirect('make-appointment')->with('err', 'Please Select A Hospital Again.');

            if (!$this->validUser($currentUser))
                return redirect('login')->with('err', 'Plese Login into an account.');

            return redirect('appointment-selected-hospital/' . $location)->with('err', $err);
        }

        $appID = $this->idGenerator('A', $this->ref_table_appointment);
        $postData = [
            'location' => $location,
            'userID' => $currentUser,
            'preferred_date' => $pre_date,
            'preferred_time' => $pre_time,
            'status' => $status,
            'result' => $result
        ];
        $data = $this->generateQrCode($postData);

        $postData['fileName'] = $this->saveFile($data);

        $record = $this->database->getReference($this->ref_table_appointment . '/' . $appID)->set($postData);

        return redirect('view-certificate/' . $currentUser);
        // return view('FrontEnd.Home.viewCertificate')->with('filename', $filename)->with('record', $record)->with('status', $status);
    }

    public function appointmentForm($id)
    {
        $currentUser = session('user.key');

        $location = $this->database->getReference($this->ref_table_hospital)->getChild($id)->getChild('Name')->getValue();
        $user = $this->database->getReference($this->ref_table_user)->getChild($currentUser)->getValue();

        return view('FrontEnd.Home.appointmentForm')->with('location', $location)->with('user', $user)->with('hospitalID', $id);
    }

    // ----------------------------------
    //        REPORT GENERATOR
    // ----------------------------------
    public function downloadResult()
    {
        $userKey = session('user.key');
        $data = $this->getResult($userKey);
        $fileName = 'Blood Test Result - ' . session('user.name');

        // Load the view and get the HTML content
        $html = view('FrontEnd.Report.report', $data)->render();

        // Set DOMPDF options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Set the paper size to match the web page size
        $options->set('defaultPaperSize', 'A4');

        // Instantiate the DOMPDF class
        $dompdf = new Dompdf($options);

        // Load HTML content into DOMPDF
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF (download)
        return $dompdf->stream($fileName . '.pdf');
    }


    public function getResult($userKey)
    {
        $appointmentList = $this->database->getReference($this->ref_table_appointment)->getValue();
        $appointmentInfo = null;
        foreach ($appointmentList as $key => $item) {
            $currentUser = $this->database->getReference($this->ref_table_appointment)->getChild($key)->getChild('userID')->getValue();
            if ($currentUser == $userKey) {
                $appointmentInfo = $appointmentList[$key];
            }
        }
        $userInfo = $this->database->getReference($this->ref_table_user)->getChild($userKey)->getValue();
        $hospital = $this->database->getReference($this->ref_table_hospital)->getChild($appointmentInfo['location'])->getChild('Name')->getValue();

        $bloodType = null;
        switch ($appointmentInfo['result']['bloodType']) {
            case 'a-Positive':
                $bloodType = 'A+';
                break;
            case 'a-Negative':
                $bloodType = 'A-';
                break;
            case 'b-Positive':
                $bloodType = 'B+';
                break;
            case 'b-Negative':
                $bloodType = 'B-';
                break;
            case 'o-Positive':
                $bloodType = 'O+';
                break;
            case 'o-Negative':
                $bloodType = 'O-';
                break;
            case 'ab-Positive':
                $bloodType = 'AB+';
                break;
            case 'ab-Negative':
                $bloodType = 'AB-';
                break;
        }

        $data = [
            'userName' => $userInfo['name'],
            'ic' => $userInfo['identityCard'],
            'gender' => $userInfo['gender'],
            'hospital' => $hospital,
            'bloodType' => $bloodType,
            'result' => $appointmentInfo['result']['testResult'],
            'date' => $appointmentInfo['result']['testDate']
        ];

        return $data;
    }

    // ----------------------------------
    //        QR CODE GENERATOR
    // ----------------------------------
    protected function generateQrCode($validatedData)
    {
        // Generate the  data
        $data = $this->generateData($validatedData);
        // Generate QR code with UTF-8 character set
        $qrCode = QrCode::size(300)->generate($data);

        return $qrCode;
    }

    public function saveFile($qrCode)
    {
        // Define the directory where QR code images will be stored
        $qrCodeDirectory = public_path('appQR/');

        // Ensure the directory exists; if not, create it
        if (!file_exists($qrCodeDirectory)) {
            mkdir($qrCodeDirectory, 0755, true);
        }

        // Generate a unique filename for the QR code image
        $filename = 'qr_code_' . uniqid() . '.png';

        // Save the QR code image to the file system
        $qrCodePath = $qrCodeDirectory . $filename;
        file_put_contents($qrCodePath, $qrCode);
        return $filename;
    }

    protected function generateData($array): string
    {
 
        $formattedString = '';

        // Iterate through the array and format each key-value pair
        foreach ($array as $key => $value) {
            // Check if $value is an array
            if (is_array($value)) {
                // If it's an array, convert it to JSON format
                $value = json_encode($value);
            }
            // Append the key and value to the formatted string
            $formattedString .= ucfirst($key) . ': ' . $value . "\n &emsp;"; // Capitalize the key
        }
        return $formattedString;
    }
    // ----------------------------------
    //        Validation Data
    // ----------------------------------

    public function getErrMessage($location, $currentUser, $pre_date, $pre_time)
    {
        $err = [];

        if (!$this->validDate($pre_date)) {
            $err[] = 'Date cannot before today and more than 3 month';
        }

        if (!$this->validTime($pre_time)) {
            $err[] = 'Please select between 8.00am until 10.00pm';
        }
        return $err;
    }
    public function validHospital($key)
    {
        $hospitalList = $this->database->getReference($this->ref_table_hospital)->getValue();
        return array_key_exists($key, $hospitalList);
    }
    public function validUser($key)
    {
        $userList = $this->database->getReference($this->ref_table_user)->getValue();
        return array_key_exists($key, $userList);
    }

    public function validDate($value)
    {
        $today = Carbon::now();
        $inputDate = Carbon::parse($value)->startOfDay();
        $threeMonthsFromNow = $today->copy()->addMonths(3);

        return $inputDate->isCurrentDay() || $inputDate->isAfter($today) && $threeMonthsFromNow->greaterThanOrEqualTo($inputDate);
    }

    public function validTime($value)
    {
        $time = Carbon::createFromFormat('H:i', $value);
        // Define the start and end times for validation
        $start = Carbon::createFromTime(8, 0, 0); // 8 AM
        $end = Carbon::createFromTime(22, 0, 0);  // 10 PM

        return $time->between($start, $end);
    }
}
