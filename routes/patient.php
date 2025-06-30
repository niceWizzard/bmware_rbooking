<?php
use Inertia\Inertia;


Route::prefix('/patient')
    ->middleware(['auth'])->name('patient')->group(function () {

    Route::get('/dashboard', static function () {
            return Inertia::render('Dashboard');
    })->name('.dashboard');

});
