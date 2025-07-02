<?php

use App\Models\Doctor;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static function () {
    return Inertia::render('Welcome');
});




require __DIR__.'/patient.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
