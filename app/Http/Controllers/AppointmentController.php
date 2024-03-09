<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Options;
use Dompdf\Dompdf;

class AppointmentController extends Controller
{
    //
    protected $database;
    protected $ref_table_hospital;
    protected $ref_table_user;
    protected $ref_table_appointment;
    public function __construct(Database $database)
    {
        $this->ref_table_hospital = "Hospital";
        $this->ref_table_user = "Users";
        $this->ref_table_appointment = "Appointment";
        $this->database = $database;
    }

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
        if ($appList != null) {
            foreach ($appList as $key => $item) {
                if ($item['userID'] == $id) {
                    $record = $appList[$key];
                    $status = $item['status'];
                }
            }
        }

        if($record == null || $status == null){
            return redirect('make-appointment');
        }

        return view('FrontEnd.Home.viewCertificate')->with('record', $record)->with('status', $status);
    }

    public function editResult(Request $req)
    {
        $childKey = 'result';
        $chilKey2 = 'status';
        $status = 'Done';
        $appointmentID = $req->appID;
        $bloodType = $req->bloodType;
        $bloodResult = $req->result;
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

        $appID = $this->idGenerator('A', $this->ref_table_appointment);
        $postData = [
            'location' => $location,
            'userID' => $currentUser,
            'preferred_date' => $pre_date,
            'preferred_time' => $pre_time,
            'status' => $status,
            'result' => $result
        ];
        $filename = $this->generateQrCode($postData);
        $postData['fileName'] = $filename;

        $record = $this->database->getReference($this->ref_table_appointment . '/' . $appID)->set($postData);

        return view('FrontEnd.Home.viewCertificate')->with('filename', $filename)->with('record', $record)->with('status', $status);
    }

    public function appointmentForm($id)
    {
        $currentUser = session('user.key');

        $location = $this->database->getReference($this->ref_table_hospital)->getChild($id)->getChild('Name')->getValue();
        $user = $this->database->getReference($this->ref_table_user)->getChild($currentUser)->getValue();

        return view('FrontEnd.Home.appointmentForm')->with('location', $location)->with('user', $user)->with('hospitalID', $id);
    }

    public function idGenerator($letter, $ref_collection)
    {

        $today = Carbon::now();
        $year = $today->year;
        $month = $today->month;

        //GET LATEST RECORD
        // $reference = app('firebase.firestore')->database()->collection($ref_collection)->orderBy($item, 'DESC')->limit(1)->documents();
        // $lastRecord = collect($reference->rows());
        $lastID = $this->database->getReference($ref_collection)->orderByKey()->limitToLast(1)->getValue();
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
        // return view('FrontEnd.Report.report');
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
        // Generate the parcel data
        $data = $this->generateData($validatedData);

        // Generate QR code with UTF-8 character set
        $qrCode = QrCode::format('png')->size(200)->encoding('UTF-8')->generate('SIMPLE DATA');
        //dd($qrCode);

        // Define the directory where QR code images will be stored
        $qrCodeDirectory = public_path('appQR/');

        // Ensure the directory exists; if not, create it
        if (!file_exists($qrCodeDirectory)) {
            mkdir($qrCodeDirectory, 0755, true);
        }

        // Generate a unique filename for the QR code image
        $filename = 'qr_code_' . uniqid() . '.jpg';

        // Save the QR code image to the file system
        $qrCodePath = $qrCodeDirectory . $filename;
        file_put_contents($qrCodePath, $qrCode);
        return $filename;
    }

    protected function generateData(array $validatedData): string
    {
        // Serialize the validated data
        $data = serialize($validatedData);

        return $data;
    }
}
