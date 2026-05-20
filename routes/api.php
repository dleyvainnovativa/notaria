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

Route::post('/checkout/memorial', [PaymentController::class, 'createGuestCheckoutSession']);
Route::get('/',)->name('api');
Route::post("/{memorial}/tributes", [TributeController::class, 'store'])->name('api.memorial.tributes.send');


Route::middleware('firebase.jwt')->group(function () {

    Route::post('/extract', [DeclaranotController::class, 'extract'])->name('document.extract');


    Route::get("/memorials", [MemorialController::class, 'index'])->name('api.memorial.memorials');
    Route::get("/{memorial}/info", [MemorialController::class, 'info'])->name('api.memorial.info');
    Route::put("/{memorial}/info", [MemorialController::class, 'update'])->name('api.memorial.update');
    Route::put("/{memorial}/privacy", [MemorialController::class, 'privacy'])->name('api.memorial.privacy');
    Route::post("/{memorial}/photo", [MemorialController::class, 'photo'])->name('api.memorial.photo');

    Route::get("/{memorial}/invitations", [MemorialController::class, 'invitations'])->name('api.memorial.invitations');
    Route::post("/{memorial}/invite", [MemorialController::class, 'invite'])->name('api.memorial.invite');
    Route::put('/invitations/{id}/permissions', [MemorialController::class, 'updatePermissions'])->name('api.memorial.invitations.permissions');

    Route::get("/{memorial}/timeline", [TimelineController::class, 'index'])->name('api.memorial.timeline');
    Route::post("/{memorial}/timeline", [TimelineController::class, 'store'])->name('api.memorial.timeline.add');
    Route::get("/{memorial}/timeline/{timelineEvent}", [TimelineController::class, 'show'])->name('api.memorial.timeline.show');
    Route::put("/{memorial}/timeline/{timelineEvent}", [TimelineController::class, 'update'])->name('api.memorial.timeline.update');
    Route::delete("/{memorial}/timeline/{timelineEvent}", [TimelineController::class, 'destroy'])->name('api.memorial.timeline.delete');

    Route::get("/{memorial}/gallery", [GalleryController::class, 'index'])->name('api.memorial.gallery');
    Route::post("/{memorial}/gallery", [GalleryController::class, 'store'])->name('api.memorial.gallery.add');
    Route::put("/{memorial}/gallery/order", [GalleryController::class, 'updateOrder'])->name('api.memorial.gallery.order');
    Route::delete("/{memorial}/gallery/{mediaItem}", [GalleryController::class, 'destroy'])->name('api.memorial.gallery.delete');

    Route::get("/{memorial}/tributes", [TributeController::class, 'index'])->name('api.memorial.tributes');
    Route::patch("/{memorial}/tributes/{tribute}/approve", [TributeController::class, 'approve'])->name('api.memorial.tributes.approve');
    Route::patch("/{memorial}/tributes/{tribute}/reject", [TributeController::class, 'reject'])->name('api.memorial.tributes.reject');

    Route::get("/{memorial}/life", [MemorialParagraphController::class, 'index'])->name('api.memorial.life');
    Route::post("/{memorial}/life", [MemorialParagraphController::class, 'update'])->name('api.memorial.life.update');

    Route::get("/payments", [PaymentsController::class, 'index'])->name('api.memorial.payments');;
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
