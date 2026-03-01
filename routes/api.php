<?php

use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Sanctum Token Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Mevcut kullanıcı bilgisi
    Route::get('/user', function (Request $request) {
        return $request->user()->only([
            'id',
            'name',
            'email',
            'role',
            'is_premium',
            'social_links',
            'likes_count',
        ]);
    });

    // Beğeni endpoint'leri (rate-limited in controller)
    Route::post('/likes', [LikeController::class, 'store']);
    Route::delete('/likes', [LikeController::class, 'destroy']);

    // Destek biletleri
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
});
