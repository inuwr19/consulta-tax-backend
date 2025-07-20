<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ConsultantController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/consultants', ConsultantController::class);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('chats', ChatController::class)->only(['index', 'store']);
    Route::post('/payments/create-snap-token', [PaymentController::class, 'createSnapToken']);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::post('/payments/midtrans', [PaymentController::class, 'createSnapToken']);
    Route::post('/payments/update-status', [PaymentController::class, 'updateStatus']);

    // profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/user', [UserController::class, 'update']);
    Route::patch('/user', [UserController::class, 'update']);

});
