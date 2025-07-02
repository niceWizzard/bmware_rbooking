<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DoctorScheduleController extends Controller
{
    public function create(Request $request, string $id)
    {
        $doctor = Doctor::findOrFail($id);
        if($doctor->schedules()->exists()) {
            return redirect(route('admin.dashboard'));
        }
        $user = Auth::user();
        if(!$user->is_admin) {
            return redirect(route('patient.dashboard'));
        }

        Auth::user()->load('admin');
        return Inertia::render('Admin/CreateSchedule',  [
            'doctor' => $doctor,
        ]);
    }

    public function store(Request $request, string $id) {
        $doctor = Doctor::findOrFail($id);
        $user = Auth::user();
        if(!$user->is_admin || $doctor->schedules()->exists()) {
            return redirect()->back()->with('message', "You cannot create a schedule for this doctor.",);
        }
        $data = $request->validate([
            'events.*.clinic' => ['required', 'string'],
            'events.*.day' => ['required', 'numeric'],
            'events.*.start' => ['required', 'date'],
            'events.*.end' => ['required', 'date'],
        ]);

        foreach ($data['events'] as $event) {
            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'clinic' => $event['clinic'],
                'day' => $event['day'],
                'start' => Carbon::parse($event['start'])->toTimeString(),
                'end' => Carbon::parse($event['end'])->toTimeString(),
            ]);
        }

        return redirect($user->getDashboardLink());
    }
}
