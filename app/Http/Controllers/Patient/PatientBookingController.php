<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentDetails;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

class PatientBookingController extends Controller
{
    public function showDoctorList(Request $request): Response
    {
        $doctors = Doctor::has('schedules')->get();

        return Inertia::render('Patient/Schedule/DoctorList', [
            'doctors' => $doctors,
        ]);
    }

    public function showBooking(Request $request)
    {
        $code = $request->query('code');

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

        $slots = $this->getBookingSlots($doctor);

        return Inertia::render('Patient/Schedule/Booking', [
            'invalid' => false,
            'doctor' => $doctor,
            'slots' => $slots,
            'hiddenDays' => $doctor->getDaysOff(),
            'timeRange' => $doctor->getScheduleTimeRange(),
            'dateRange' => [
                now()->toDateString(),
                now()->addDays(15)->toDateString(),
            ],
        ]);
    }

    public function fetchBookingSlots(Request $request) {
        $code = $request->query('code');

        if(is_null($code)) {
            $code = Cookie::get('doctorCode');
        }
        if(is_null($code)) {
            return \response()->json([
                'success' => false,
                'message' => 'Please provide a doctor code.',
            ]);
        }
        $doctor = Doctor::whereCode($code)->first();
        $user = Auth::user();

        if(is_null($doctor)) {
            return \response()->json([
                'success' => false,
                'message' => 'Invalid doctor code given.',
            ]);
        }

        $slots = $this->getBookingSlots($doctor);

        return \response()->json([
            'success' => true,
            'slots' => $slots,
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

        if(!Auth::user()->canStillBookToday()) {
            return \response()->json([
               'success'=> false,
               'message' => 'You can only book up to 3 appointments per day.'
            ]);
        }

        if(
            Appointment::isDoctorBookedAt($doctor, $date) ||
            Appointment::isPatientBookedAt(Auth::user()->patient, $date)
        ) {
            return \response()->json([
                'success' => false,
                'message' => 'Doctor/Patient already booked this date.',
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



    public static function getAvailableSlots(Doctor $doctor, User $user): array
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
                    'id' =>  $slot->toDateTimeString(),
                ];
            }
        }
        return $availableSlots;
    }

    /**
     * @param Doctor $doctor
     * @return array
     */
    public static function getBookingSlots(Doctor $doctor): array
    {
        $slots = self::getAvailableSlots($doctor, \Auth::user());

        foreach (Auth::user()->getAppointmentsWith($doctor->id) as $appointment) {
            $slots[] = [
                'id' => $appointment->id,
                'start' => $appointment->appointment_start_date_time,
                'end' => $appointment->appointment_end_date_time,
                'color' => 'gray',
                'title' => 'Appointment ',
                'type' => 'booked',
                'clinic' => $appointment->clinic,
                'booked_at' => $appointment->created_at,
            ];
        }
        return $slots;
    }

}
