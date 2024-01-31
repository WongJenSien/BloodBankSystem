<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
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

Route::get('view-inventory', [InventoryController::class,'show']);
Route::get('remove-inventory', [InventoryController::class, 'shipOut']);

// Route::get('/', function () {
//     return view('welcome');
// });