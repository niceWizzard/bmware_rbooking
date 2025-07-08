<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Patient\PatientBookingController;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function delete(string $id): JsonResponse
    {
        $appointment = Appointment::find($id);
        if (is_null($appointment)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, appointment with id ' . $id . ' cannot be found'
            ]);
        }
        if (Auth::user()->patient_id !== $appointment->patient_id && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you are not allowed to delete this appointment',
            ]);
        }
        $appointment->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function showChangeTime(string $id) {
        $appointment = Appointment::findOrFail($id);
        $doctor = $appointment->details->doctor;
        $slots = self::getChangeTimeSlots($appointment);
        return Inertia::render('Patient/Appointment/ChangeTime', [
            'slots' => $slots,
            'doctor' => $doctor,
            'hiddenDays' => $doctor->getDaysOff(),
            'timeRange' => $doctor->getScheduleTimeRange(),
            'dateRange' => [
                now()->toDateString(),
                now()->addDays(15)->toDateString(),
            ],
            'appointmentId' => $appointment->id,
        ]);
    }

    public function fetchChangeTimeSlots(string $id) {
        $appointment = Appointment::find($id);
        if(is_null($appointment)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, appointment with id ' . $id . ' cannot be found'
            ]);
        }
        if(Auth::user()->patient_id !== $appointment->patient_id) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you are not allowed to change this appointment',
            ]);
        }
        $slots = self::getChangeTimeSlots($appointment);
        return response()->json([
            'success' => true,
            'slots' => $slots,
        ]);
    }

    public function changeTime(string $id, Request $request): JsonResponse
    {
        $appointment = Appointment::find($id);
        if (is_null($appointment)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, appointment with id ' . $id . ' cannot be found'
            ]);
        }

        if ($appointment->patient_id !== Auth::user()->patient_id) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you are not allowed to change this appointment',
            ]);
        }

        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = Carbon::parse($request->date);

        if(
            Appointment::isPatientBookedAt(
                Auth::user()->patient,
                $date
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you are already booked this appointment',
            ]);
        }
        if(Appointment::isDoctorBookedAt(
            $appointment->details->doctor,
            $date
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, doctor is already taken at this time.',
            ]);
        }

        $appointment->update([
            'appointment_start' => $date->toTimeString(),
            'appointment_end' => $date->copy()->addHour()->toTimeString(),
            'appointment_date' => $date->toDateString(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|Appointment $appointment
     * @return array
     */
    public static function getChangeTimeSlots(\Illuminate\Database\Eloquent\Collection|Appointment $appointment): array
    {
        return [
            ...PatientBookingController::getAvailableSlots($appointment->details->doctor, Auth::user()),
            [
                'title' => 'Current',
                'id' => $appointment->id,
                'type' => 'current',
                'color' => 'gray',
                'start' => $appointment->appointment_start_date_time,
                'end' => $appointment->appointment_end_date_time,
            ]
        ];
    }

}
