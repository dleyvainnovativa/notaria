<?php

use App\Http\Controllers\Admin\MemorialParagraphController as AdminMemorialParagraphController;
use App\Http\Controllers\Api\DeclaranotController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\MemorialParagraphController;
use App\Http\Controllers\Api\MemorialController;
use App\Http\Controllers\Api\PaymentsController;
use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\Api\TributeController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentController;

Route::get('/',)->name('api');
Route::post('/checkout/memorial', [PaymentController::class, 'createGuestCheckoutSession']);
Route::middleware('firebase.jwt')->group(function () {

    Route::post('/declaranot/extract', [DeclaranotController::class, 'extract'])->name('api.declaranot.extract');
    Route::post('/declaranot/export', [DeclaranotController::class, 'export'])->name('api.declaranot.export');


    Route::get("/payments", [PaymentsController::class, 'index'])->name('api.memorial.payments');
    // --- FCM Token Storage ---
    Route::post('/fcm-tokens', function (Request $request) {
        $request->validate(['token' => 'required|string']);
        $adminUser = $request->user();

        if ($adminUser) {
            $adminUser->fcmTokens()->updateOrCreate(
                ['token' => $request->input('token')],
                ['user_id' => $adminUser->id]
            );
            return response()->json(['message' => 'Token stored successfully.']);
        }
        return response()->json(['message' => 'Authentication failed.'], 401);
    });
});
