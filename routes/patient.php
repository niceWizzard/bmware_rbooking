<?php

use App\Http\Controllers\Patient\PatientBookingController;
use Inertia\Inertia;


Route::prefix('/patient')
    ->middleware(['auth'])
    ->name('patient')->group(function () {

    Route::get('/dashboard', static function () {
        Auth::user()->load('patient');
        return Inertia::render('Patient/Dashboard');
    })->name('.dashboard');

    Route::controller(PatientBookingController::class)->group(function () {
        Route::get('/doctors', 'showDoctorList')->name('.doctors');

        Route::get('/book', 'showBooking')->name('.book');
    });


});
