<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Controllers\Admin\MemorialsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Api\QRController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StripeWebhookController;
use App\Models\Memorial;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are for browser-based, server-rendered pages. They are
| part of the 'web' middleware group (sessions, CSRF, etc.).
|
*/

// --- PUBLIC-FACING BOOKING SITE ---

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');
Route::get('/instructions', function () {
    return view('instructions');
})->name('instructions');
// Route::get('/payment', [PageController::class, 'book'])->name('payment');
Route::get('/test', [PageController::class, 'test'])->name('test');
Route::get('/q/{qr:uuid}', [QRController::class, 'index'])->name('qr.index');
Route::get('/q/{qr:uuid}/download', [QRController::class, 'download'])->name('qr.download');
Route::get('/memory/{memorial}', [PageController::class, 'memory'])->name('memory');
Route::post('/memory/{memorial}', [PageController::class, 'memory_protected'])->name('memory.protected');

// Stripe redirect routes (user is sent back here from an external site)
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('/payment/confirm', [StripeWebhookController::class, 'handle']);

Route::get('/login', function () {
    if (session()->has('firebase_uid')) {
        return redirect(route("admin"));
    } else {
        return view('admin.login');
    }
})->name('login');
Route::get('/register', function () {
    return view('admin.register');
})->name('register');
Route::post('/forget', [AuthController::class, 'forget'])->name('forget');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', function () {
    return view('admin.pages.logout');
})->name('admin.logout');
Route::post('/auth/firebase', [AuthController::class, 'firebaseLogin']);

Route::prefix('admin')->middleware('firebase.auth')->group(function () {

    Route::get('/payment', [PageController::class, 'book'])->name('payment');

    // Route::get('/', function () {
    //     return view('admin.pages.memorials');
    // })->name('admin');
    Route::get('/', [MemorialsController::class, 'index'])->name('admin');

    // Route::get('/memorials', function () {
    //     return view('admin.pages.memorials');
    // })->name('admin.memorials');
    Route::get('/memorials', [MemorialsController::class, 'index'])->name('admin.memorials');

    // Route::get('/payments', function () {
    //     return view('admin.pages.payments');
    // })->name('admin.payments');

    Route::get('/payments', [PaymentsController::class, 'index'])->name('admin.payments');
    Route::get('/document', [DocumentsController::class, 'index'])->name('admin.document');
    Route::get('/invoice/{id}', [PaymentsController::class, 'invoice'])->name('admin.invoice');
    Route::get('/invoice/{id}/download', [PaymentsController::class, 'download'])->name('admin.invoice.download');



    Route::prefix('memorial/{memorial}')->middleware('memorial.access')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.memorial.dashboard');
        Route::get('/life', function (Memorial $memorial) {
            return view('admin.pages.life', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user')
            ]);
        })->name('admin.memorial.life');
        Route::get('/timeline', function (Memorial $memorial) {
            return view('admin.pages.timeline', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user'),

            ]);
        })->name('admin.memorial.timeline');
        Route::get('/info', function (Memorial $memorial) {
            return view('admin.pages.info', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user')
            ]);
        })->name('admin.memorial.info');
        Route::get('/gallery', function (Memorial $memorial) {
            return view('admin.pages.gallery', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user')
            ]);
        })->name('admin.memorial.gallery');
        Route::get('/playlist', function (Memorial $memorial) {
            return view('admin.pages.playlist', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user')
            ]);
        })->name('admin.memorial.playlist');
        Route::get('/messages', function (Memorial $memorial) {
            return view('admin.pages.messages', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user')
            ]);
        })->name('admin.memorial.messages');
        Route::get('/invitations', function (Memorial $memorial) {
            return view('admin.pages.invitations', [
                'memorial_slug' => $memorial->slug,
                'memorial' => $memorial,
                'user' => request()->route('current_user')
            ]);
        })->name('admin.memorial.invitations');
    });


    Route::get('/{any}', function () {
        return view('admin.pages.memorials');
    })->where('any', '.*');
});
