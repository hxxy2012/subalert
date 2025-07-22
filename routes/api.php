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

// 认证相关API
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 需要认证的API路由
Route::middleware('auth:sanctum')->group(function () {
    // 订阅相关API
    Route::apiResource('subscriptions', \App\Http\Controllers\Api\SubscriptionController::class);
    Route::post('/subscriptions/{subscription}/renew', [\App\Http\Controllers\Api\SubscriptionController::class, 'renew']);
    
    // 提醒相关API
    Route::apiResource('reminders', \App\Http\Controllers\Api\ReminderController::class);
    Route::post('/reminders/{reminder}/read', [\App\Http\Controllers\Api\ReminderController::class, 'markAsRead']);
    
    // 统计相关API
    Route::prefix('statistics')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\StatisticsController::class, 'index']);
        Route::get('/dashboard', [\App\Http\Controllers\Api\StatisticsController::class, 'dashboard']);
        Route::get('/subscriptions', [\App\Http\Controllers\Api\StatisticsController::class, 'subscriptions']);
        Route::get('/reminders', [\App\Http\Controllers\Api\StatisticsController::class, 'reminders']);
        Route::get('/monthly-expenses', [\App\Http\Controllers\Api\StatisticsController::class, 'monthlyExpenses']);
        Route::get('/expiring', [\App\Http\Controllers\Api\StatisticsController::class, 'expiringSubscriptions']);
    });
});