<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleBaseController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/index-backend', function () {
    return view('BackEnd.Home.index');
});

// ------------------------------------------
//            INVENTORY CONTROLLER
// ------------------------------------------
Route::get('view-inventory', [InventoryController::class,'index']);
Route::get('add-inventory', [InventoryController::class,'create']);
Route::post('add-inventory', [InventoryController::class,'store']);
Route::get('remove-inventory', [InventoryController::class, 'shipOut']);

// ------------------------------------------
//            SHIPMENT CONTROLLER
// ------------------------------------------

Route::get('view-shipment',[ShipmentController::class, 'index']);
Route::post('remove-inventory', [ShipmentController::class, 'store']);
Route::get('shipment-view-detials/{id}',[ShipmentController::class, 'show']);
Route::post('shipment-edit-status',[ShipmentController::class, 'editStatus']);

// ------------------------------------------
//            REPORT CONTROLLER
// ------------------------------------------
Route::get('inventory-report',[ReportController::class,'showInventoryReport']);
Route::get('shipment-report',[ReportController::class,'shipmentReport']);
Route::get('download-inventory-report',[ReportController::class, 'downloadPDF']);

// ------------------------------------------
//            APPOINTMENT CONTROLLER
// ------------------------------------------
Route::get('view-certificate/{id}',[AppointmentController::class,'show']);
Route::get('make-appointment',[AppointmentController::class,'create']);
Route::get('appointment-selected-hospital/{id}',[AppointmentController::class,'appointmentForm']);
Route::post('store-appointment',[AppointmentController::class,'store']);
Route::get('appointment-list',[AppointmentController::class,'index']);
Route::post('insert-bloodtest-result',[AppointmentController::class,'editResult']);
Route::get('download-result',[AppointmentController::class,'downloadResult']);
Route::get('cancel-appointment/{id}',[AppointmentController::class,'destroy']);


Route::post('edit-permission',[RoleBaseController::class,'editPermission']);
Route::resource('role-base-control',RoleBaseController::class);


// Route::get('add-event', [EventController::class,'create']);

// ----------------------------------------------------------
//                     GAVIN YOH ROUTE
// ----------------------------------------------------------


Route::get('/', [HomeController::class,'index'])->name('landing');


// ----  USER MODULE ------
Route::get('/login', [HomeController::class,'loginForm'])->name('loginForm');
Route::post('/login', [HomeController::class,'login'])->name('login');

Route::get('/profile', [HomeController::class,'profileForm'])->name('profileForm');
Route::post('/profile', [HomeController::class,'profile'])->name('profile');
Route::get('/register', [HomeController::class,'registerForm'])->name('registerForm');
Route::post('/register', [HomeController::class,'register'])->name('register');
Route::get('/logout', [HomeController::class,'logout'])->name('logout');
Route::get('/forgotPassword', [HomeController::class,'forgotPasswordForm'])->name('forgotPasswordForm');
Route::post('/forgotPassword', [HomeController::class,'forgotPassword'])->name('forgotPassword');
Route::get('/changePassword/{token}', [HomeController::class,'changePasswordForm'])->name('changePasswordForm');
Route::post('/changePassword', [HomeController::class,'changePassword'])->name('changePassword');
Route::get('/editProfile', [HomeController::class,'editProfile'])->name('editProfile');

// ----  EVENT MODULE ------
Route::get('/eventList', [HomeController::class,'eventList'])->name('eventList');
Route::get('/addEvent', [HomeController::class,'addEventForm'])->name('addEventForm');
Route::post('/addEvent', [HomeController::class,'addEvent'])->name('addEvent');
Route::get('/deleteEvent/{key}', [HomeController::class,'deleteEvent'])->name('deleteEvent');
Route::get('/updateEvent/{key}', [HomeController::class,'updateEventForm'])->name('updateEventForm');
Route::post('/updateEvent', [HomeController::class,'updateEvent'])->name('updateEvent');
Route::get('/viewEvent/{key}', [HomeController::class,'viewEventForm'])->name('viewEventForm');
Route::get('/deleteUser/{key}', [HomeController::class,'deleteUser'])->name('deleteUser');
Route::get('/updateUser/{key}', [HomeController::class,'updateUserForm'])->name('updateUserForm');
Route::post('/updateUser', [HomeController::class,'updateUser'])->name('updateUser');
Route::get('/viewUser/{key}', [HomeController::class,'viewUserForm'])->name('viewUserForm');


Route::get('/event', [HomeController::class,'event'])->name('event');
Route::get('/attend/{key}', [HomeController::class,'attend'])->name('attend');
Route::get('/user', [HomeController::class,'userList'])->name('userList');
Route::get('/addReward/{key}', [HomeController::class,'addRewardForm'])->name('addRewardForm');
Route::post('/addReward/{key}', [HomeController::class,'addReward'])->name('addReward');
Route::get('/eventReport/{key}', [HomeController::class,'eventReport'])->name('eventReport');
Route::get('/addAdmin', [HomeController::class,'addAdminForm'])->name('addAdminForm');
Route::post('/addAdmin', [HomeController::class,'addAdmin'])->name('addAdmin');
Route::get('/cancel/{key}', [HomeController::class,'cancel'])->name('cancel');
Route::get('/removeReward/{key}', [HomeController::class,'removeReward'])->name('removeReward');