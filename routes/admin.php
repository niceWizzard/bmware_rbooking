<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminInfoController;
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

        Route::post('', [AdminInfoController::class, 'update'])->name('.profile');
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
            'profile_picture' => ['required', 'file', 'max:2048']
        ]);
        $path = $request->file('profile_picture')?->store('profile_pictures', 'public');
        $doctor = Doctor::create([
            ...$data,
            'profile_picture' => $path,
        ]);
        return redirect(route('schedule.create', $doctor->id));
    });

    Route::get('/create', static function () {
        return Inertia::render('Admin/Doctor/DoctorCreate');
    })->name('.create');

    Route::get('/', static function () {
        $doctors = Doctor::withExists('schedules')->get();
        return Inertia::render('Admin/Doctor/DoctorList', [
            'doctors' => $doctors,
        ]);
    })->name('.list');

    Route::delete('/{id}', static function (string $id) {
        $doctor = Doctor::findOrFail($id);
        if(!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        $doctor->delete();
        return response()->json([
            'success' => true,
        ]);
    })->name('.delete');
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
