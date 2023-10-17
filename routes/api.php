<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/doUpdateCustomerProfile', [AuthController::class, 'doUpdateCustomerProfile']);
Route::middleware('auth:sanctum')->post('/doUpdateUserProfile', [AuthController::class, 'doUpdateUserProfile']);
Route::middleware('auth:sanctum')->post('/doChangePassword', [AuthController::class, 'doChangePassword']);

Route::apiResource('coupon', CouponController::class);
Route::apiResource('season', SeasonController::class);
Route::middleware('auth:sanctum')->apiResource('service', ServiceController::class);
