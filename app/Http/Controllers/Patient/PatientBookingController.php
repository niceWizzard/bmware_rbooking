<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

class PatientBookingController extends Controller
{
    public function showDoctorList(Request $request): Response
    {
        $doctors = Doctor::all();

        return Inertia::render('Patient/Schedule/DoctorList', [
            'doctors' => $doctors,
        ]);
    }

    public function showBooking(Request $request): Response
    {
        $code = $request->query('code');
        \Auth::user()->load('patient');

        if(is_null($code)) {
            $code = Cookie::get('doctorCode');
        }
        if(is_null($code)) {
            return Inertia::render('Patient/Schedule/Booking', [
                'invalid' => true,
            ]);
        }
        $doctor = Doctor::whereCode($code)->first();

        if(is_null($doctor)) {
            return Inertia::render('Patient/Schedule/Booking', [
                'invalid' => true,
            ]);
        }
        Cookie::queue(Cookie::make('doctorCode', $doctor->code, 24 * 60 * 7));

        return Inertia::render('Patient/Schedule/Booking', [
            'invalid' => false,
            'doctor' => $doctor,
        ]);


    }
}
