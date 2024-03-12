<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;


class HomeController extends Controller
{
    protected $database;
    public function __construct(Database $database)
    {
        $this->database = $database;
    }


    public function index()
    {
        return view("FrontEnd.Home.index");
    }

    public function loginForm()
    {
        if($this->checkLogin()){
            return redirect()->route('profileForm');
        }

        return view("BackEnd.Home.login");
    }

    public function profileForm()
    {
        if(!$this->checkLogin()){
            return redirect()->route('logout')->with('status', 'Please Login');
        }

        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo(session('user.emailAddress'));
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();
            $user = reset($userData);

            session()->put('user.name', $user['name']);

            $ref_tablename = 'Participants';
            $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo(session('user.key'));
            $snapshot = $reference->getSnapshot();
            $data = $snapshot->getValue();
            $endData = end($data);

            if ($endData === false) {
                $activity = [];
            }else{
                $ref_tablename = 'Events';
                $reference = $this->database->getReference($ref_tablename . '/' . $endData['eventID']);
                $snapshot = $reference->getSnapshot();
    
                $activity = [];
                if ($snapshot->exists()) {
                    $activity = $snapshot->getValue();
                }
            }

            $ref_tablename = 'Rewards';
            $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo(session('user.key'));
            $snapshot = $reference->getSnapshot();
            $data = $snapshot->getValue();
            $endData = end($data);

            if ($endData === false) {
                $reward = [];
            }else{
                $reward = $endData;
                $reward['qr'] = QrCode::size(150)->generate($reward['code']);
            }
        }else{
            $this->logout();
        }

        return view("BackEnd.Home.profile", compact('user', 'activity', 'reward'));
    }

    public function registerForm()
    {
        if($this->checkLogin()){
            return redirect()->route('profileForm');
        }

        $gender = ['Male' => 'Male', 'Female' => 'Female'];

        return view("BackEnd.Home.register", compact('gender'));
    }

    public function register(Request $request){
        if($this->checkLogin()){
            return redirect()->route('profileForm');
        }

        $ref_tablename = 'Users';

        $userRef = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo($request->emailAddress)->getValue();
        if (!empty($userRef)) {
            return redirect()->route('registerForm')->with('status', 'Email already exists.');
        }

        $postData = [
            'emailAddress' => $request->emailAddress,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'identityCard' => $request->identityCard,
            'BOD' => $request->BOD,
            'gender' => $request->gender,
            'contactNumber' => $request->contactNumber,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'roleID' => 2,
            'path' => 'profile/default.png'
        ];

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

        if($postRef){
            return redirect()->route('loginForm')->with('status', 'Register Successfully');
        }else{
            return redirect()->route('registerForm')->with('status', 'Register Failed');
        }
    }

    public function login(Request $request){
        if($this->checkLogin()){
            return redirect()->route('profileForm');
        }
        
        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo($request->emailAddress);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();

            if(empty($userData)){
                return redirect()->back()->with('status', 'Invalid email or password.');
            }

            $recordKey = array_keys($userData);

            $user = reset($userData);
            $user['key'] = $recordKey[0];
            if (password_verify($request->password, $user['password'])) {
                session(['user' => $user]);
                return redirect()->route('profileForm')->with('status', 'Login Successfully');
            }
        }

        return redirect()->back()->with('status', 'Invalid email or password.');
    }

    public function logout(){
        session()->flush();
        return redirect()->route('loginForm');
    }

    public function forgotPasswordForm(){
        return view("BackEnd.Home.forgotpassword");
    }

    public function forgotPassword(Request $request){        
        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo($request->emailAddress);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();

            if(empty($userData)){
                return redirect()->back()->with('status', 'Email Not Found');
            }
        }

        $token = bin2hex(random_bytes(32));

        $ref_tablename = 'Tokens';

        $postData = [
            'emailAddress' => $request->emailAddress,
            'token' => $token,
            'status' => 0
        ];

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

        if($postRef){
            $data = [
                'email' => $request->emailAddress,
                'link' => route('changePasswordForm', ['token' => $token])
            ];
    
            // Mail::to($request->emailAddress)->send(new ForgotPasswordMail($data));
    
            return redirect()->route('forgotPasswordForm')->with('status', 'Forgot Password Link Sent');
        }else{
            return redirect()->route('forgotPasswordForm')->with('status', 'Forgot Password Link Sent Failed');
        }
    }

    public function changePasswordForm($token){
        $ref_tablename = 'Tokens';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('token')->equalTo($token);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $tokenData = $snapshot->getValue();

            if(empty($tokenData)){
                return redirect()->route('forgotPasswordForm')->with('status', 'Token Not Found');
            }

            $recordKey = array_keys($tokenData);

            $tokenData = reset($tokenData);
            $tokenData['key'] = $recordKey[0];

            if($tokenData['status']==1){
                return redirect()->route('forgotPasswordForm')->with('status', 'Link Expired');
            }

            return view("BackEnd.Home.changepassword", compact('tokenData'));
        }
    }

    public function changePassword(Request $request){
        if($request->password != $request->confirmpassword){
            return redirect()->route('changePasswordForm', ['token' => $request->token])->with('status', 'Password and Confirm Password Must Same');
        }

        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo($request->email);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();
            $recordKey = array_keys($userData);

            $ref_tablename = 'Users';
            $updatedData = [
                'password' => Hash::make($request->password)
            ];
            $reference = $this->database->getReference($ref_tablename . '/' . $recordKey[0]);
            $reference->update($updatedData);

            $ref_tablename = 'Tokens';
            $updatedData = [
                'status' => 1
            ];
            $reference = $this->database->getReference($ref_tablename . '/' . $request->key);
            $reference->update($updatedData);

            return redirect()->route('loginForm')->with('status', 'Password Changed');
        }
    }

    public function editProfile()
    {
        if(!$this->checkLogin()){
            return redirect()->route('logout')->with('status', 'Please Login');
        }

        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo(session('user.emailAddress'));
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();
            $recordKey = array_keys($userData);
            $user = reset($userData);
            $user['key'] = $recordKey[0];
        }else{
            $this->logout();
        }

        $gender = ['Male' => 'Male', 'Female' => 'Female'];

        return view("BackEnd.Home.editProfile", compact('user', 'gender'));
    }

    public function profile(Request $request){
        $ref_tablename = 'Users';

        $updatedData = [
            'name' => $request->name,
            'identityCard' => $request->identityCard,
            'BOD' => $request->BOD,
            'gender' => $request->gender,
            'contactNumber' => $request->contactNumber,
            'address' => $request->address,
            'postcode' => $request->postcode,
        ];

        if ($request->hasFile('photo')) {
            $fileName = $this->uploadFile($request->file('photo'), 'public/profile');
            if (!empty($fileName['path'])) {
                $updatedData['path'] = 'profile/'.$fileName['path'];
            }
        }

        $reference = $this->database->getReference($ref_tablename . '/' . $request->key);

        $reference->update($updatedData);

        return redirect()->route('profileForm')->with('status', 'Update Success');
    }

    public function eventList(Request $request){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Events';

        $page = request()->query('page', 1);
        $perPage = 100;

        $offset = ($page - 1) * $perPage;

        $reference = $this->database->getReference($ref_tablename);

        $snapshot = $reference->orderByKey()
                              ->limitToFirst($perPage)
                              ->startAt((string)$offset)
                              ->getSnapshot();

        $data = $snapshot->getValue();

        $totalRecords = $snapshot->numChildren();

        $paginationData = new LengthAwarePaginator(
            $data,
            $totalRecords,
            $perPage,
            $page,
            ['path' => route('eventList')] 
        );

        return view('BackEnd.Event.eventList', [
            'paginationData' => $paginationData,
        ]);
    }

    public function addEventForm(){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        return view("BackEnd.Event.addEvent");
    }

    public function addEvent(Request $request){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Events';

        $postData = [
            'eventName' => $request->eventName,
            'eventSponsor' => $request->eventSponsor,
            'eventVenue' => $request->eventVenue,
            'eventStartDate' => $request->eventStartDate,
            'eventEndDate' => $request->eventEndDate,
            'eventStartTime' => $request->eventStartTime,
            'eventEndTime' => $request->eventEndTime,
            'path' => 'event/default.png'
        ];

        if ($request->hasFile('photo')) {
            $fileName = $this->uploadFile($request->file('photo'), 'public/event');
            if (!empty($fileName['path'])) {
                $postData['path'] = 'event/'.$fileName['path'];
            }
        }

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

        if($postRef){
            return redirect()->route('eventList')->with('status', 'Add Event Successfully');
        }else{
            return redirect()->route('addEventForm')->with('status', 'Add Event Failed');
        }
    }

    public function deleteEvent($key){
        $ref_tablename = 'Events';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $reference->remove();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    public function updateEventForm($key){
        $ref_tablename = 'Events';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $record = $snapshot->getValue();
            $record['key'] = $key;
        }else{
            $this->logout();
        }

        return view("BackEnd.Event.updateEvent", compact('record'));
    }

    public function updateEvent(Request $request){
        $ref_tablename = 'Events';

        $updatedData = [
            'eventName' => $request->eventName,
            'eventSponsor' => $request->eventSponsor,
            'eventVenue' => $request->eventVenue,
            'eventStartDate' => $request->eventStartDate,
            'eventEndDate' => $request->eventEndDate,
            'eventStartTime' => $request->eventStartTime,
            'eventEndTime' => $request->eventEndTime,
        ];

        if ($request->hasFile('photo')) {
            $fileName = $this->uploadFile($request->file('photo'), 'public/profile');
            if (!empty($fileName['path'])) {
                $updatedData['path'] = 'profile/'.$fileName['path'];
            }
        }

        $reference = $this->database->getReference($ref_tablename . '/' . $request->key);

        $reference->update($updatedData);

        return redirect()->route('eventList')->with('status', 'Update Success');
    }

    public function viewEventForm($key){
        $ref_tablename = 'Events';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $record = $snapshot->getValue();
            $record['key'] = $key;
        }else{
            $this->logout();
        }

        return view("BackEnd.Event.viewEvent", compact('record'));
    }

    public function event(Request $request){
        $searchQuery = $request->search;

        $reference = $this->database->getReference('Events');

        if($searchQuery){
            $query = $reference->orderByChild('eventName')->equalTo($searchQuery);
        }else{
            $query = $reference->orderByChild('eventName');
        }

        $snapshot = $query->getSnapshot();

        $paginationData = $snapshot->getValue();

        $attended = [];
        if (session()->has('user')) {
            $ref_tablename = 'Participants';
            $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo(session('user.key'));
            $snapshot = $reference->getSnapshot();
            $data = $snapshot->getValue();

            foreach ($data as $item) {
                $attended[] = $item['eventID'];
            }
        }

        return view('FrontEnd.Home.event', [
            'paginationData' => $paginationData,
            'attended' => $attended
        ]);
    }

    public function attend($key){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Participants';

        $postData = [
            'userID' => session('user.key'),
            'eventID' => $key,
            'enrollDate' => date('Y-m-d H:i:s')
        ];

        $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo(session('user.key'));
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        foreach ($data as $item) {
            if ($item['eventID'] == $key) {
                return redirect()->route('event')->with('status', 'Already Attend');
            }
        }

        $ref_tablename = 'Participants';
        $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo(session('user.key'));
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();
        $endData = end($data);

        if ($endData === false) {
            //do nothing
        }else{
            $date1 = Carbon::parse(date('Y-m-d', strtotime($endData['enrollDate'])));
            $date2 = Carbon::parse(date('Y-m-d'));

            $diffInDays = $date1->diffInDays($date2);

            if($diffInDays<90){
                return redirect()->route('event')->with('status', 'Max Attend In 90 Days');
            }
        }

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

        if($postRef){
            return redirect()->route('event')->with('status', 'Attend Successfully');
        }else{
            return redirect()->route('event')->with('status', 'Attend Failed');
        }
    }

    public function userList(Request $request){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Users';

        $page = request()->query('page', 1);
        $perPage = 100;

        $offset = ($page - 1) * $perPage;

        $reference = $this->database->getReference($ref_tablename);

        $snapshot = $reference->orderByKey()
                              ->limitToFirst($perPage)
                              ->startAt((string)$offset)
                              ->getSnapshot();

        $data = $snapshot->getValue();

        $totalRecords = $snapshot->numChildren();

        foreach($data as $key => $value){
            if($value['roleID']==1){
                unset($data[$key]);
            }
        }

        $paginationData = new LengthAwarePaginator(
            $data,
            $totalRecords,
            $perPage,
            $page,
            ['path' => route('userList')] 
        );

        return view('BackEnd.User.userList', [
            'paginationData' => $paginationData,
        ]);
    }

    public function deleteUser($key){
        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $reference->remove();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    public function updateUserForm($key){
        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $record = $snapshot->getValue();
            $record['key'] = $key;
        }else{
            $this->logout();
        }

        $gender = ['Male' => 'Male', 'Female' => 'Female'];

        return view("BackEnd.User.updateUser", compact('record','gender'));
    }

    public function updateUser(Request $request){
        $ref_tablename = 'Users';

        $updatedData = [
            'name' => $request->name,
            'identityCard' => $request->identityCard,
            'BOD' => $request->BOD,
            'gender' => $request->gender,
            'contactNumber' => $request->contactNumber,
            'address' => $request->address,
            'postcode' => $request->postcode,
        ];

        if ($request->hasFile('photo')) {
            $fileName = $this->uploadFile($request->file('photo'), 'public/profile');
            if (!empty($fileName['path'])) {
                $updatedData['path'] = 'user/'.$fileName['path'];
            }
        }

        $reference = $this->database->getReference($ref_tablename . '/' . $request->key);

        $reference->update($updatedData);

        return redirect()->route('userList')->with('status', 'Update Success');
    }

    public function viewUserForm($key){
        $ref_tablename = 'Users';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $record = $snapshot->getValue();
            $record['key'] = $key;
        }else{
            $this->logout();
        }

        return view("BackEnd.User.viewUser", compact('record'));
    }

    public function addRewardForm($key){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        return view("BackEnd.User.addReward", compact('key'));
    }

    public function addReward(Request $request, $key){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Rewards';

        $postData = [
            'userID' => $key,
            'code' => $request->code,
            'name' => $request->name,
            'date' => date('Y-m-d H:i:s')
        ];

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

        if($postRef){
            return redirect()->route('userList')->with('status', 'Add Reward Successfully');
        }else{
            return redirect()->route('addRewardForm')->with('status', 'Add Reward Failed');
        }
    }

    public function eventReport($key){
        $ref_tablename = 'Events';

        $reference = $this->database->getReference($ref_tablename . '/' . $key);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $record = $snapshot->getValue();
            $record['key'] = $key;
        }else{
            $this->logout();
        }

        $ref_tablename = 'Participants';
        $reference = $this->database->getReference($ref_tablename);
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        $participant = [];
        foreach ($data as $item) {
            if($item['eventID'] != $key){
                continue;
            }

            $ref_tablename = 'Users';
            $reference = $this->database->getReference($ref_tablename . '/' . $item['userID']);
            $snapshot = $reference->getSnapshot();

            if ($snapshot->exists()) {
                $userData = $snapshot->getValue();
                $item['user'] = $userData['name'];
            }else{
                $item['user'] = 'Unknown User';
            }

            $participant[] = $item;
        }
        
        return view("BackEnd.Event.eventReport", compact('record', 'participant'));
    }

    public function addAdminForm(){
        if(!$this->checkLogin()){
            return redirect()->route('logout')->with('status', 'Please Login');
        }

        $gender = ['Male' => 'Male', 'Female' => 'Female'];

        return view("BackEnd.User.addAdmin", compact('gender'));
    }

    public function addAdmin(Request $request){
        $ref_tablename = 'Users';

        $userRef = $this->database->getReference($ref_tablename)->orderByChild('emailAddress')->equalTo($request->emailAddress)->getValue();
        if (!empty($userRef)) {
            return redirect()->route('addAdminForm')->with('status', 'Email already exists.');
        }

        $postData = [
            'emailAddress' => $request->emailAddress,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'identityCard' => $request->identityCard,
            'BOD' => $request->BOD,
            'gender' => $request->gender,
            'contactNumber' => $request->contactNumber,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'roleID' => 1,
            'path' => 'profile/default.png'
        ];

        if ($request->hasFile('photo')) {
            $fileName = $this->uploadFile($request->file('photo'), 'public/profile');
            if (!empty($fileName['path'])) {
                $postData['path'] = 'profile/'.$fileName['path'];
            }
        }

        $postRef = $this->database->getReference($ref_tablename)->push($postData);

        if($postRef){
            return redirect()->route('profileForm')->with('status', 'Add Admin Successfully');
        }else{
            return redirect()->route('addAdminForm')->with('status', 'Add Admin Failed');
        }
    }

    public function cancel($key){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Participants';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo(session('user.key'));
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        foreach ($data as $attendkey => $item) {
            if ($item['eventID'] == $key) {
                $reference = $this->database->getReference($ref_tablename . '/' . $attendkey);
                $reference->remove();
                break;
            }
        }

        return redirect()->route('event')->with('status', 'Cancel Successfully');
    }

    public function removeReward($key){
        if(!$this->checkLogin()){
            return redirect()->route('logout');
        }

        $ref_tablename = 'Rewards';

        $reference = $this->database->getReference($ref_tablename)->orderByChild('userID')->equalTo($key);
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        foreach ($data as $rewardKey => $item) {
            $reference = $this->database->getReference($ref_tablename . '/' . $rewardKey);
            $reference->remove();
        }

        return redirect()->route('userList')->with('status', 'Remove Reward Successfully');
    }
}
