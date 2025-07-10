<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AdminInfoController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PatientInfoController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\Patient\PatientBookingController;
use App\Http\Controllers\Patient\PatientVerifyController;
use App\Http\Controllers\PatientManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', static function () {
    if(Auth::check()) {
        return redirect(Auth::user()->getDashboardLink());
    }
    return redirect(route('patient.doctors'));
});

//AUTH
Route::middleware(['guest'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store');
        Route::post('/logout', 'destroy')
            ->withoutMiddleware('guest')
            ->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', static function () {
            return Inertia::render('Auth/Register');
        })->name('register');

        Route::post('/register', 'store');
    });


    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

});


Route::middleware(['auth'])->group(function () {
    Route::controller(EmailVerificationController::class)->group(function() {
        Route::get('verify-email', 'prompt')
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', 'verify')
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('verify-email/notification', 'sendEmail')
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });

    Route::prefix('/profile')->name('profile')->group(function () {

        Route::controller(ProfileController::class)
            ->group(function () {
                Route::get('/', 'show')->name('.edit');
                Route::post('/', 'update')->name('.update');
                Route::delete('/', 'destroy')->name('.destroy');
            });

        Route::post('/password', [PasswordController::class, 'update'])
            ->name('.password');
    });

});


// PATIENT
Route::prefix('/patient')
    ->middleware(['auth', 'verified','only.patient', 'patient.verified'])
    ->name('patient')->group(function () {

        Route::controller(PatientBookingController::class)->group(function () {
            Route::get('/doctors', 'showDoctorList')
                ->withoutMiddleware(['only.patient', 'auth'])
                ->name('.doctors');
            Route::get('/book/refetch', 'fetchBookingSlots')->name('.book.fetch');
            Route::get('/book', 'showBooking')->name('.book');
            Route::post('/book', 'bookAppointment');
        });

        Route::controller(AppointmentController::class)
            ->prefix('/appointment/{id}')
            ->name('.appointment')
            ->withoutMiddleware('only.patient')
            ->group(function () {
                Route::delete('/', 'delete')->name('.delete');
                Route::get('/change', 'showChangeTime')->name('.change');
                Route::post('/change', 'changeTime');

                Route::get('/change/fetch', 'fetchChangeTimeSlots')->name('.change.fetch');
            });

        Route::post('/', [PatientInfoController::class, 'update'])->name('.profile');

        Route::prefix('/verify')->name('.verify')
            ->controller(PatientVerifyController::class)
            ->withoutMiddleware('patient.verified')
            ->group(function () {
                Route::get('/', 'verifyPatient')->name('.index');
                Route::post('/', 'verify')->name('.verify');
            });
    });



// ADMIN
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
                Route::get('/{id}/edit', 'edit')->name('.edit');
                Route::post('/{id}', 'update')->name('.update');
            });

        Route::prefix('/schedule/{id}')
            ->name('schedule')
            ->controller(DoctorScheduleController::class)
            ->group(static function () {
                Route::get('/create', 'create')->name('.create');
                Route::post('/create', 'store')->name('.store');
                Route::get('/', 'view')->name('.view');
                Route::get('/edit', 'edit')->name('.edit');
                Route::post('/edit', 'update')->name('.update');
            });

        Route::prefix('/patient')
            ->name('patient')
            ->controller(PatientManagementController::class)
            ->group(static function () {
                Route::get('/', 'index')->name('.list');
                Route::delete('/{id}', 'delete')->name('.delete');
            });
    });

