<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\OperatorCodeController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResources([
        'users' => UserController::class,
        'drivers' => DriverController::class,
        'balances' => BalanceController::class,
        'vehicles' => VehicleController::class,
        'operators' => OperatorController::class,
        'inspectors' => InspectorController::class,
        'parkings' => ParkingController::class,
        'reservations' => ReservationController::class,
        'stops' => StopController::class,
        'operator_codes' => OperatorCodeController::class,
    ]);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/reset', [AuthController::class, 'resetPassword'])->name('reset');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
