<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeclaranotController as AdminDeclaranotController;
use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Controllers\Admin\MemorialsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Api\CatalogosController;
use App\Http\Controllers\Api\QRController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\DeclaranotController;
use App\Http\Controllers\Api\PagosFileController;
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

Route::get('/extract', [DeclaranotController::class, 'extract'])->name('document.extract');
Route::get('/declaranot', [DeclaranotController::class, 'generate'])->name('document.generate');
Route::get('/pagos', [PagosFileController::class, 'extract'])->name('pagos.extract');
Route::get('/catalogos', [CatalogosController::class, 'catalogos'])->name('pagos.catalogos');
Route::get('/catalogos/excel', [CatalogosController::class, 'exportCatalogsToExcel'])->name('pagos.catalogos.excel');
Route::get('/catalogos/store', [CatalogosController::class, 'import'])->name('pagos.catalogos.import');

// --- PUBLIC-FACING BOOKING SITE ---

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

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
    Route::get('/', [DashboardController::class, 'index'])->name('admin');
    Route::get('/payments', [PaymentsController::class, 'index'])->name('admin.payments');
    Route::get('/document', [AdminDeclaranotController::class, 'index'])->name('admin.declaranot');

    Route::prefix('memorial/{memorial}')->middleware('memorial.access')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.memorial.dashboard');
    });

    Route::get('/{any}', function () {
        return view('admin.pages.memorials');
    })->where('any', '.*');
});
