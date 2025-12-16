<?php

use App\Http\Controllers\WishlistController;
use App\Http\Controllers\GiftController;
use Illuminate\Support\Facades\Route;

Route::prefix('wishlists')->group(function () {
    Route::post('/', [WishlistController::class, 'store']);
    Route::put('/{token}', [WishlistController::class, 'update']);
    Route::delete('/{token}', [WishlistController::class, 'destroy']);
    Route::get('/{token}', [WishlistController::class, 'showOwnerView']);
});

Route::prefix('public')->group(function () {
    Route::get('/{token}', [WishlistController::class, 'showPublicView']);
    Route::post('gifts/{gift_id}/book', [GiftController::class, 'book']);
    Route::delete('gifts/{gift_id}/unbook', [GiftController::class, 'unbook']);
});