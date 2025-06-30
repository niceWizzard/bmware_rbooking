<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store');
        Route::post('/logout', 'destroy')->name('logout');

    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', static function () {
            return Inertia::render('Auth/Register');
        })->name('register');

        Route::post('/register', 'store');
    });
});
