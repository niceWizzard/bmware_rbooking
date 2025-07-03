<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function delete(string $id): JsonResponse
    {
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
                'message' => 'Sorry, you are not allowed to delete this appointment',
            ]);
        }
        $appointment->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
