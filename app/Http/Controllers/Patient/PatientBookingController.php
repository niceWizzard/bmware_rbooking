<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentDetails;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;
use function Termwind\parse;

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

    public function bookAppointment(Request $request) {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'code' => ['required', 'exists:booking_doctors,code'],
        ]);

        $doctor = Doctor::whereCode($data['code'])->first();
        if(is_null($doctor)) {
            return \response()->json([
                'success' => false,
                'message' => 'Doctor not found',
            ]);
        }

        $date = Carbon::parse($data['date']);

        if($date->getTimezone()->getName() !== 'UTC') {
            return \response()->json([
                'success' => false,
                'message' => 'Invalid date. Must be in UTC format.',
            ]);
        }

        $schedule = $doctor->schedules()->where('day',$date->dayOfWeek)->first();
        if(is_null($schedule)) {
            return \response()->json([
                'success' => false,
                'message' => 'Schedule not found' . $date->toString(),
            ]);
        }

        $appointment = Appointment::create([
            'clinic' => $schedule->clinic,
            'patient_id' => \Auth::user()->patient_id,
            'appointment_start' => $date->toTimeString(),
            'appointment_date' => $date->toDateString(),
            'appointment_end' => $date->copy()->addHour()->toTimeString(),
            'appointment_for' => 'In-Patient',
        ]);

        $details = AppointmentDetails::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $doctor->id,
            'patient_id' => \Auth::user()->patient_id,
        ]);

        return \response()->json([
            'success' => true,
            'appointment' => $appointment->id,
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
