<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\MockSmsController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/user-details', [AuthController::class, 'getUserDetails']);
    Route::post('/send-sms', [SmsController::class, 'send']);
    Route::post('/send-bulk-sms', [SmsController::class, 'sendBulk']);
    Route::get('/sms-report', [SmsController::class, 'getReport']);
    Route::get('/sms-report-details', [SmsController::class, 'getDetail']);
});

Route::middleware('throttle:10000,1')->post('/send-mock-sms', [MockSmsController::class, 'send']);