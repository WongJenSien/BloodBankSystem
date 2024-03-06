<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ReportController;
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

Route::get('/', [HomeController::class,'index']);
Route::get('login', [HomeController::class,'login']);
Route::post('profile', [HomeController::class,'profile']);

Route::get('add-inventory', [InventoryController::class,'create']);
Route::post('add-inventory', [InventoryController::class,'store']);

Route::get('view-inventory', [InventoryController::class,'index']);
Route::get('view-shipment',[ShipmentController::class, 'index']);

Route::get('remove-inventory', [InventoryController::class, 'shipOut']);
Route::post('remove-inventory', [ShipmentController::class, 'store']);

Route::get('shipment-view-detials/{id}',[ShipmentController::class, 'show']);
Route::post('shipment-edit-status',[ShipmentController::class, 'editStatus']);


Route::get('inventory-report',[ReportController::class,'showInventoryReport']);
Route::get('shipment-report',[ReportController::class,'shipmentReport']);

Route::get('download-inventory-report',[ReportController::class, 'downloadPDF']);



Route::get('add-event', [EventController::class,'create']);