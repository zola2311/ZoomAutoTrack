<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiclePassportController;
use App\Http\Controllers\VoiceRecordingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentProofController;

Route::get('/', function () {
    return view('welcome');
});
// Public Digital Vehicle Passport — no auth, resolved by qr_code UUID.
Route::get('/passport/{qrCode}', [VehiclePassportController::class, 'show'])
    ->name('passport.show');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/payment-proofs/{payment}', [PaymentProofController::class, 'show'])
        ->name('payment-proofs.show');
    Route::post('/voice-recordings', [VoiceRecordingController::class, 'store'])
        ->name('voice-recordings.store');
    // Test route - visit this to verify the controller works
    Route::get('/voice-test', [VoiceRecordingController::class, 'test'])->name('voice-test');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', function () {return view('settings.index');})->name('settings.index');
    Route::get('/users', function () {return view('users.index');})->name('users.index');
});

Route::middleware(['auth', 'role:admin|manager'])->group(function () {
    Route::get('/reports', function () {return view('reports.index');})->name('reports.index');
});
//Route::middleware(['auth', 'permission:view job cards|manage job cards|view assigned job cards'])->group(function () {
//    Route::get('/job-cards', [JobCardController::class, 'index'])->name('job-cards.index');
//});
//Route::middleware(['auth', 'permission:create job cards|manage job cards'])->group(function () {
//    Route::get('/job-cards/create', [JobCardController::class, 'create'])->name('job-cards.create');
//    Route::post('/job-cards', [JobCardController::class, 'store'])->name('job-cards.store');
//});

require __DIR__.'/auth.php';
