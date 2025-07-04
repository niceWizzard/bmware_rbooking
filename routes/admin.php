<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DoctorScheduleController;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::prefix('/admin')
    ->name('admin')
    ->middleware(['auth', 'only.admin'])
    ->group(static function () {
        Route::get('/dashboard', [DashboardController::class,'index'])->name('.dashboard');
        Route::get('/dashboard/fetch', [DashboardController::class,'fetchAppointmentSlots'])->name('.dashboard.fetch');
    });

Route::middleware(['auth', 'only.admin'])
    ->prefix('/admin/doctors')
    ->name('doctor')
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
        return Inertia::render('Admin/Doctor/DoctorCreate');
    })->name('.create');

    Route::get('/', static function () {
        Auth::user()->load('admin');
        $doctors = Doctor::withExists('schedules')->get();
        return Inertia::render('Admin/Doctor/DoctorList', [
            'doctors' => $doctors,
        ]);
    })->name('.list');
});

Route::middleware(['auth','only.admin'])
->prefix('/schedule/{id}')
->name('schedule')
->controller(DoctorScheduleController::class)
->group(static function () {
    Route::get('/create', 'create')->name('.create');
    Route::post('/create', 'store');
    Route::get('/', 'view')->name('.view');

    Route::get('/edit', 'edit')->name('.edit');
    Route::post('/edit', 'update');
});
