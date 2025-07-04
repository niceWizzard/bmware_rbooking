<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $code = $request->query('code');
        \Auth::user()->load('patient');

        if(is_null($code)) {
            $code = Cookie::get('doctorCode');
        }
        if(is_null($code)) {
            return redirect(route('patient.doctors'));
        }
        $doctor = Doctor::whereCode($code)->first();

        if(is_null($doctor)) {
            return redirect(route('patient.doctors'));
        }
        Cookie::queue(Cookie::make('doctorCode', $doctor->code, 24 * 60 * 7));

        $slots = self::getAppointmentSlots($doctor);
        Auth::user()->load('admin');
        return Inertia::render('Admin/Dashboard', [
            'doctor' => $doctor,
            'timeRange' => $doctor->getScheduleTimeRange(),
            'slots' => $slots,
        ]);
    }

    public function fetchAppointmentSlots(Request $request) {
        $code = $request->query('code');
        if(is_null($code)) {
            $code = Cookie::get('doctorCode');
        }
        if(is_null($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid doctor code'
            ]);
        }
        $doctor = Doctor::whereCode($code)->first();
        if(is_null($doctor)) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'slots' => self::getAppointmentSlots($doctor),
        ]);

    }

    public static function getAppointmentSlots(Doctor $doctor): array
    {
        return $doctor->appointments->map(function (Appointment $appointment) {
            return [
                'id' => $appointment->id,
                'clinic' => $appointment->clinic,
                'doctor' => $appointment->details->doctor->name,
                'start' => $appointment->appointment_start_date_time,
                'end' => $appointment->appointment_end_date_time,
                'title' => $appointment->clinic,
            ];
        })->toArray();
    }
}
