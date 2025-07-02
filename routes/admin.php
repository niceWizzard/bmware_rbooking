<?php

use App\Http\Controllers\DoctorScheduleController;
use App\Models\Doctor;
use Illuminate\Http\Request;
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

Route::middleware('auth')
    ->prefix('/doctors')
    ->name('doctors')
    ->group(static function () {

    Route::post('/create', static function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'unique:booking_doctors,code'],
            'specialty' => ['required', 'string'],
        ]);

        $doctor = Doctor::create($data);
        return redirect(route('schedule.create', $doctor->id));
    });

    Route::get('/create', static function () {
        Auth::user()->load('admin');
        return Inertia::render('DoctorCreate');
    })->name('.create');

    Route::get('/', static function () {
        Auth::user()->load('admin');
        return Inertia::render('DoctorList', [
            'doctors' => Doctor::all()
        ]);
    })->name('.list');
});

Route::middleware(['auth'])
->prefix('/schedule/{id}')
->name('schedule')
->controller(DoctorScheduleController::class)
->group(static function () {
    Route::get('/create', 'create')->name('.create');
    Route::post('/create', 'store');
    Route::get('/', 'view')->name('.view');
});
