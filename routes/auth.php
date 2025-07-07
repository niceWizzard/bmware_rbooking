<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store');
        Route::post('/logout', 'destroy')
            ->withoutMiddleware('guest')
            ->name('logout');

    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', static function () {
            return Inertia::render('Auth/Register');
        })->name('register');

        Route::post('/register', 'store');
    });
});


Route::middleware(['auth'])->group(function () {
    Route::controller(EmailVerificationController::class)->group(function() {
        Route::get('verify-email', 'prompt')
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', 'verify')
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('verify-email/notification', 'sendEmail')
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });

    Route::prefix('/profile')->name('profile')->group(function () {

        Route::controller(ProfileController::class)
            ->group(function () {
            Route::get('/', 'show')->name('.edit');
            Route::post('/', 'update')->name('.update');
            Route::delete('/', 'destroy')->name('.destroy');
        });

        Route::post('/password', [PasswordController::class, 'update'])
            ->name('.password');
    });

});
