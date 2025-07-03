<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Patient\PatientBookingController;
use Inertia\Inertia;


Route::prefix('/patient')
    ->middleware(['auth', 'only.patient'])
    ->name('patient')->group(function () {

    Route::controller(PatientBookingController::class)->group(function () {
        Route::get('/doctors', 'showDoctorList')
            ->withoutMiddleware(['only.patient', 'auth'])
            ->name('.doctors');

        Route::get('/book/refetch', 'fetchBookingSlots')->name('.book.fetch');

        Route::get('/book', 'showBooking')->name('.book');
        Route::post('/book', 'bookAppointment');
    });

    Route::controller(AppointmentController::class)
        ->prefix('/appointment/{id}')
        ->name('.appointment')
        ->group(function () {
            Route::delete('/', 'delete')->name('.delete');
            Route::get('/change', 'showChangeTime')->name('.change');
            Route::post('/change', 'changeTime');
    });


});
