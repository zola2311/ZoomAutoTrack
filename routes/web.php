<?php

use App\Http\Controllers\Auth\CustomerPasswordController;
use App\Http\Controllers\Auth\CustomerVerificationController;
use App\Http\Controllers\Portal\AppointmentController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\PrintController;

use App\Http\Controllers\PublicAppointmentController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\VehiclePassportController;
use App\Http\Controllers\VehicleQrStickerController;
use App\Http\Controllers\VoiceRecordingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/team', [PageController::class, 'team'])->name('team');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/book-a-service', [PublicAppointmentController::class, 'create'])->name('booking.create');
Route::post('/book-a-service', [PublicAppointmentController::class, 'store'])->name('booking.store');

// Quotation & Price Estimation Questionnaire
Route::get('/request-a-quote', [QuotationController::class, 'create'])->name('quotation.create');
Route::get('/quote', fn () => redirect()->route('quotation.create'));
Route::get('/get-a-quote', fn () => redirect()->route('quotation.create'));
Route::post('/request-a-quote', [QuotationController::class, 'store'])->name('quotation.store');
Route::get('/quote-confirmation/{quotationNumber}', [QuotationController::class, 'confirmation'])->name('quotation.confirmation');
Route::get('/quote/{quotationNumber}/pdf', [QuotationController::class, 'downloadPdf'])->name('quotation.pdf');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Public Digital Vehicle Passport — no auth, resolved by qr_code UUID.
Route::get('/passport-lookup', [VehiclePassportController::class, 'lookup'])
    ->name('passport.lookup');
Route::get('/passport/{qrCode}', [VehiclePassportController::class, 'show'])
    ->name('passport.show');
Route::get('/passport/{qrCode}/certificate', [VehiclePassportController::class, 'certificate'])
    ->name('passport.certificate');

Route::get('/dashboard', function () {
    if (auth('customer')->check()) {
        return redirect()->route('portal.dashboard');
    }
    if (auth('web')->check()) {
        return redirect('/admin');
    }
    return redirect()->route('portal.login');
})->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::get('/payment-proofs/{payment}', [PaymentProofController::class, 'show'])
        ->name('payment-proofs.show');
    Route::post('/voice-recordings', [VoiceRecordingController::class, 'store'])
        ->name('voice-recordings.store');
    Route::get('/voice-test', [VoiceRecordingController::class, 'test'])->name('voice-test');
    Route::get('/vehicles/{vehicle}/qr-sticker', [VehicleQrStickerController::class, 'show'])
        ->name('vehicles.qr-sticker');

    Route::get('/invoices/{invoice}/print', [PrintController::class, 'invoice'])->name('invoices.print');
    Route::get('/job-cards/{jobCard}/print', [PrintController::class, 'jobCard'])->name('job-cards.print');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', function () { return redirect('/admin/system-setup'); })->name('settings.index');
    Route::get('/users', function () { return redirect('/admin/users'); })->name('users.index');
});

Route::middleware(['auth', 'role:admin|manager'])->group(function () {
    Route::get('/reports', function () { return redirect('/admin/daily-revenue-report'); })->name('reports.index');
});

Route::prefix('portal')->name('portal.')->group(function () {

    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login'])
            ->middleware('throttle:5,1');
        Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register'])->middleware('throttle:5,1');

        Route::post('/forgot-password', [CustomerPasswordController::class, 'sendResetLink'])
            ->middleware('throttle:5,1')->name('password.email');
        Route::get('/set-password/{token}', [CustomerPasswordController::class, 'showSetForm'])->name('password.set');
        Route::post('/set-password', [CustomerPasswordController::class, 'update'])->name('password.update');
        Route::get('/forgot-password', [CustomerPasswordController::class, 'showForgotForm'])->name('password.request');
    });

    // Logged in, verification not required yet — avoids the redirect loop.
    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        Route::get('/verify-email', [CustomerVerificationController::class, 'notice'])->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [CustomerVerificationController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::post('/verify-email/resend', [CustomerVerificationController::class, 'resend'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });

    // Logged in AND verified — the actual portal.
    Route::middleware(['auth:customer', 'verified.customer'])->group(function () {
        Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/history', [PortalController::class, 'history'])->name('history');

        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    });



});
