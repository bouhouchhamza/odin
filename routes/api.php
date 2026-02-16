<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\V1\ActivityLogController as ApiActivityLogController;
use App\Http\Controllers\Api\V1\FavoriteController as ApiFavoriteController;
use App\Http\Controllers\Api\V1\LinkController as ApiLinkController;
use App\Http\Controllers\Api\V1\NotificationController as ApiNotificationController;
use App\Http\Controllers\Api\V1\ShareController as ApiShareController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/token', [AuthTokenController::class, 'store'])->middleware('guest');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/token', [AuthTokenController::class, 'destroy']);

    Route::get('/links', [ApiLinkController::class, 'index']);
    Route::post('/links', [ApiLinkController::class, 'store']);
    Route::get('/links/{link}', [ApiLinkController::class, 'show']);
    Route::patch('/links/{link}', [ApiLinkController::class, 'update']);
    Route::delete('/links/{link}', [ApiLinkController::class, 'destroy']);
    Route::patch('/links/{link}/restore', [ApiLinkController::class, 'restore']);
    Route::delete('/links/{link}/force-delete', [ApiLinkController::class, 'forceDelete']);

    Route::post('/links/{link}/shares', [ApiShareController::class, 'store']);
    Route::patch('/links/{link}/shares/{user}', [ApiShareController::class, 'update']);
    Route::delete('/links/{link}/shares/{user}', [ApiShareController::class, 'destroy']);

    Route::post('/links/{link}/favorite', [ApiFavoriteController::class, 'store']);
    Route::delete('/links/{link}/favorite', [ApiFavoriteController::class, 'destroy']);

    Route::get('/activity-logs', [ApiActivityLogController::class, 'index']);

    Route::get('/notifications', [ApiNotificationController::class, 'index']);
    Route::patch('/notifications/{notification}/read', [ApiNotificationController::class, 'markRead']);
    Route::patch('/notifications/read-all', [ApiNotificationController::class, 'markAllRead']);
});
