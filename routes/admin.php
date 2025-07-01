<?php

use App\Http\Controllers\DoctorScheduleController;
use Inertia\Inertia;

Route::prefix('/admin')
->name('admin')
->middleware(['auth'])
->group(static function () {
    Route::get('/dashboard', static function () {
        Auth::user()->load('admin');
        return Inertia::render('Admin/Dashboard');
    })->name('.dashboard');


    Route::prefix('/doctor')->group(static function () {
        Route::controller(DoctorScheduleController::class)
            ->prefix('/schedule')
            ->name('.schedule')
            ->group(static function () {
            Route::get('/create', 'index')->name('.create');
        });
    });

});
