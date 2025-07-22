<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// 订阅相关API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/subscriptions', [\App\Http\Controllers\Api\SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [\App\Http\Controllers\Api\SubscriptionController::class, 'store']);
    Route::get('/subscriptions/{subscription}', [\App\Http\Controllers\Api\SubscriptionController::class, 'show']);
    Route::put('/subscriptions/{subscription}', [\App\Http\Controllers\Api\SubscriptionController::class, 'update']);
    Route::delete('/subscriptions/{subscription}', [\App\Http\Controllers\Api\SubscriptionController::class, 'destroy']);
    
    // 提醒相关API
    Route::get('/reminders', [\App\Http\Controllers\Api\ReminderController::class, 'index']);
    Route::post('/reminders', [\App\Http\Controllers\Api\SubscriptionController::class, 'store']);
    
    // 统计相关API
    Route::get('/statistics', [\App\Http\Controllers\Api\StatisticsController::class, 'index']);
});