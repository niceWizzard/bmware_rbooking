<?php
use Inertia\Inertia;


Route::prefix('/patient')
    ->middleware(['auth'])->name('patient')->group(function () {

    Route::get('/dashboard', static function () {
        Auth::user()->load('patient');
        return Inertia::render('Patient/Dashboard');
    })->name('.dashboard');

});
