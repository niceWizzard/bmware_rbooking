<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
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
        $doctor = Doctor::whereCode($code)->firstOrFail();

        return Inertia::render('Patient/Schedule/Booking', [
            'doctor' => $doctor,
        ]);
    }
}
