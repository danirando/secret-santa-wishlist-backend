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

// Public routes for guests (Milestone 4)
Route::get('/wishlist/{uuid}', [WishlistController::class, 'showPublicView']);
Route::post('/gifts/{id}/book', [GiftController::class, 'book']);