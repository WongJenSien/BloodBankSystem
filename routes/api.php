<?php

use App\Http\Controllers\AppointmentAPIController;

use App\Http\Controllers\InventoryAPIController;
use App\Http\Controllers\UserAPIController;
use App\Http\Controllers\ReportAPIController;
use App\Http\Controllers\ShipmentAPIController;
use App\Http\Controllers\EventAPIController;
use App\Http\Controllers\RoleBaseAPIController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// -------------------------------------------
//         Inventory API Controller
// -------------------------------------------
Route::get('/view/inventory/getNewId', [InventoryAPIController::class, 'getNewId']);
Route::get('/view/inventory/getShipmentID', [InventoryAPIController::class, 'shipOut']);
Route::get('/view/inventory/displayInventoryForm', [InventoryAPIController::class, 'displayInventoryForm']);
Route::post('/view/inventory/editInventory', [InventoryAPIController::class, 'edit']);

// -------------------------------------------
//         Shipment API Controller
// -------------------------------------------
Route::put('/view/shipment/editStatus/{id}', [ShipmentAPIController::class, 'editStatus']);

// -------------------------------------------
//         Report API Controller
// -------------------------------------------
Route::get('view/report', [ReportAPIController::class, 'showInventoryReport']);
Route::get('/view/report/downloadPDF', [ReportAPIController::class, 'downloadPDF']);

// -------------------------------------------
//         Appointment API Controller
// -------------------------------------------
Route::get('/view/appointment/displayHospital', [AppointmentAPIController::class, 'getHospitalList']);
Route::get('/view/appointment/appointmentForm', [AppointmentAPIController::class, 'appointmentForm']);
Route::post('/view/appointment/editResult',[AppointmentAPIController::class,'editResult']);
Route::get('/view/result/{id}',[AppointmentAPIController::class,'downloadResult']);

// -------------------------------------------
//         User API Controller
// -------------------------------------------
Route::post('/view/login', [UserAPIController::class, 'login']);


// -------------------------------------------
//         Role Base Access Control (RBAC) API Controller
// -------------------------------------------
Route::get('/view/rbac/index',[RoleBaseAPIController::class, 'index']);
Route::post('/view/rbac/editPermission',[RoleBaseAPIController::class,'editPermission']);
Route::get('/view/rbac/validatePermission',[RoleBaseAPIController::class,'validatePermission']);

// -------------------------------------------
//         Resource  Controller
// -------------------------------------------
Route::resource('/view/event', EventAPIController::class);
Route::resource('/view/inventory', InventoryAPIController::class);
Route::resource('/view/shipment', ShipmentAPIController::class);
Route::resource('/view/appointment', AppointmentAPIController::class);
Route::resource('/view/user', UserAPIController::class);
Route::resource('/view/rbac', RoleBaseAPIController::class);