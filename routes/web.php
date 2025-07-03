<?php

use App\Models\Doctor;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static function () {
    if(Auth::check()) {
        return redirect(Auth::user()->getDashboardLink());
    }
    return redirect(route('patient.doctors'));
});


require __DIR__.'/patient.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
