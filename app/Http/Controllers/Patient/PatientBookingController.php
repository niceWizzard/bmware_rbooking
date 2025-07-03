<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'slots' => self::getAvailableSlots($doctor, \Auth::user())
        ]);


    }

    private static function getAvailableSlots(Doctor $doctor, User $user): array
    {
        $today = Carbon::now()->startOfDay();
        $availableSlots = [];

        // Loop through the next 13 days (excluding today)
        for ($i = 1; $i < 15; $i++) {
            $date = $today->copy()->addDays($i); // avoid mutating $today
            [$slots, $schedule] = $doctor->availableSlotsAt($date, $user);
            foreach ($slots as $slot) {
                $availableSlots[] = [
                    'title' => 'Available',
                    'start' => $slot->toDateTimeString(),
                    'end' => $slot->copy()->addHour()->toDateTimeString(),
                    'type' => 'free',
                    'clinic' => $schedule?->clinic ?? 'NULL',
                    'doctor' => $doctor->name,
                    'id' =>  $slot->toDateTimeString(),
                ];
            }
        }
        return $availableSlots;
    }

}
