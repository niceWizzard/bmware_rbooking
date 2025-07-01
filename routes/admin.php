<?php

use Inertia\Inertia;

Route::prefix('/admin')
->name('admin')
->middleware(['auth'])
->group(static function () {
    Route::get('/dashboard', static function () {
        Auth::user()->load('admin');
        return Inertia::render('Admin/Dashboard');
    })->name('.dashboard');
});
