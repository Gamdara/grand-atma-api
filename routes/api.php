<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
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


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/doGetRoomAvailability', [RoomController::class, 'getRoomAvailability']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::post('/doUpdateCustomerProfile', [AuthController::class, 'doUpdateCustomerProfile']);
    Route::post('/doUpdateUserProfile', [AuthController::class, 'doUpdateUserProfile']);
    Route::post('/doChangePassword', [AuthController::class, 'doChangePassword']);

    Route::apiResource('coupon', CouponController::class);
    Route::apiResource('season', SeasonController::class);
    Route::post('doInsertFare/{season}', [SeasonController::class, 'addFare']);
    Route::put('doUpdateFare/{season}', [SeasonController::class, 'updateFare']);
    Route::delete('doDeleteFare/{season}', [SeasonController::class, 'deleteFare']);

    Route::apiResource('customer', CustomerController::class);
    Route::get('/doGetCustomerReservationGroup/{customer}', [CustomerController::class, 'getCustomerReservationGroup']);
    Route::get('/doGetReservationGroup', [ReservationController::class, 'getReservationGroup']);

    Route::post('doInsertRoom/{room}', [RoomController::class, 'addRoom']);
    Route::put('doUpdateRoom/{room}', [RoomController::class, 'updateRoom']);
    Route::delete('doDeleteRoom/{room}', [RoomController::class, 'deleteRoom']);

    Route::apiResource('room', RoomController::class);
    Route::apiResource('reservation', ReservationController::class);

    Route::apiResource('service', ServiceController::class);
});
Route::post('/doAddReservation', [ReservationController::class, 'addReservation']);


