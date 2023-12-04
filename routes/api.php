<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckInOutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
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
Route::post('/doResetPassword', [AuthController::class, 'doResetPassword']);

Route::post('/doGetRoomAvailability', [RoomController::class, 'getRoomAvailability']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/doGetCustomerBaru', [ReportController::class, 'getCustomerBaru']);
    Route::post('/doCetakCustomerBaru', [ReportController::class, 'cetakCustomerBaru']);

    Route::post('/doGetPendapatan', [ReportController::class, 'getPendapatan']);
    Route::post('/doCetakPendapatan', [ReportController::class, 'cetakPendapatan']);
    Route::post('/doGetJumlahTamu', [ReportController::class, 'getJumlahTamu']);
    Route::post('/doCetakJumlahTamu', [ReportController::class, 'cetakJumlahTamu']);
    Route::post('/doGetReservasiTerbanyak', [ReportController::class, 'getReservasiTerbanyak']);
    Route::post('/doCetakReservasiTerbanyak', [ReportController::class, 'cetakReservasiTerbanyak']);

    Route::post('/doConfirmReservation/{reservation}', [ReservationController::class, 'doConfirmReservation']);

    Route::get('/doGetCheckinAble', [CheckInOutController::class, 'getCheckinAble']);
    Route::get('/doGetCheckoutAble', [CheckInOutController::class, 'getCheckoutAble']);
    Route::post('/doAddLayanan/{reservation}', [CheckInOutController::class, 'addLayanan']);
    Route::get('/doCheckIn/{reservation}', [CheckInOutController::class, 'checkIn']);
    Route::post('/doCheckOut/{reservation}', [CheckInOutController::class, 'checkOut']);
    Route::get('/doGetNota/{reservation}', [CheckInOutController::class, 'getNota']);

    Route::get('/doGetTandaTerima/{reservation}', [ReservationController::class, 'getTandaTerima']);
    Route::post('/doGetReservationReport/{reservation}', [ReservationController::class, 'getReservationReport']);

    Route::post('/doConfirmReservationGroup/{reservation}', [ReservationController::class, 'doConfirmReservationGroup']);
    Route::get('/doCancelReservation/{reservation}', [ReservationController::class, 'doCancelReservation']);

    Route::get('/doGetReservationHistory', [ReservationController::class, 'getReservationHistory']);
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


    Route::post('/doAddReservation', [ReservationController::class, 'addReservation']);
    Route::apiResource('reservation', ReservationController::class);

});
Route::apiResource('room', RoomController::class);
Route::apiResource('service', ServiceController::class);


