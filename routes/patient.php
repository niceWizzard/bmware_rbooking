<?php

use App\Http\Controllers\Patient\PatientBookingController;
use Inertia\Inertia;


Route::prefix('/patient')
    ->middleware(['auth'])
    ->name('patient')->group(function () {

    Route::controller(PatientBookingController::class)->group(function () {
        Route::get('/doctors', 'showDoctorList')->name('.doctors');

        Route::get('/book', 'showBooking')->name('.book');
    });


});
