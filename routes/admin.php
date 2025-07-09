<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Auth\AdminInfoController;
use App\Http\Controllers\DoctorScheduleController;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::middleware(['auth', 'only.admin'])
->group(static function () {

    Route::prefix('/admin')
        ->name('admin')
        ->group(static function () {
            Route::get('/dashboard', [DashboardController::class,'index'])->name('.dashboard');
            Route::get('/dashboard/fetch', [DashboardController::class,'fetchAppointmentSlots'])->name('.dashboard.fetch');
            Route::post('/', [AdminInfoController::class, 'update'])->name('.profile');
    });


    Route::prefix('/admin/doctors')
        ->name('doctor')
        ->controller(DoctorController::class)
        ->group(static function () {

        Route::post('/create', 'store');
        Route::get('/create', 'create')->name('.create');
        Route::get('/', 'index')->name('.list');
        Route::delete('/{id}', 'delete')->name('.delete');
    });

    Route::prefix('/schedule/{id}')
    ->name('schedule')
    ->controller(DoctorScheduleController::class)
    ->group(static function () {
        Route::get('/create', 'create')->name('.create');
        Route::post('/create', 'store');
        Route::get('/', 'view')->name('.view');
        Route::get('/edit', 'edit')->name('.edit');
        Route::post('/edit', 'update');
    });
});
