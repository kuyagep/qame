<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\RegistrationController;
use App\Http\Middleware\EnsurePrivacyAccepted;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    // 1. Public Privacy Notice Screen
    Route::get('privacy-notice', function () {
        return view('pages.privacy');
    })->name('privacy.notice');

    Route::post('privacy-notice/accept', function () {
        // Store acceptance marker in the user's session container for 45 minutes
        session(['privacy_accepted' => true]);
        return redirect()->route('register');
    })->name('privacy.accept');

    Route::get('registration-pending', function () {
        return view('pages.registration-pending');
    })->name('registration.pending');

    // Attach the protection layer directly onto your register view hooks
    Route::get('register', [RegistrationController::class, 'showRegistrationForm'])
        ->middleware(EnsurePrivacyAccepted::class)
        ->name('register');

    Route::post('register', [RegistrationController::class, 'store'])
        ->middleware(EnsurePrivacyAccepted::class);

    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
