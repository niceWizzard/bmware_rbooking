<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function view(string $id)
    {
        $doctor = Doctor::findOrFail($id);

        if(!$doctor->schedules()->exists()) {
            return redirect()->route('doctor.list');
        }
        $slots = $doctor->schedules->map(fn (DoctorSchedule $s) => [
            'id' => $s->id,
            'clinic' => $s->clinic,
            'start' => $s->start_at,
            'end' => $s->end_at,
            'title' => $s->clinic,
            'day' => $s->day,
        ]);
        return Inertia::render('Admin/Doctor/ViewSchedule', [
            'doctor' => $doctor,
            'slots' => $slots,
            'range' => $doctor->getScheduleTimeRange(),
        ]);
    }

    public function edit(string $id) {
        $doctor = Doctor::findOrFail($id);

        if(!$doctor->schedules()->exists()) {
            return redirect()->route('doctor.list');
        }

        $schedule = $doctor->schedules->map(function (DoctorSchedule $schedule) {
           return [
               'id' => $schedule->id,
               'start' => $schedule->start_at->toDateTimeString(),
               'end' => $schedule->end_at->toDateTimeString(),
               'clinic' => $schedule->clinic,
               'title' => 'Schedule',
           ];
        });
        return Inertia::render('Admin/Doctor/EditSchedule', [
            'doctor' => $doctor,
            'schedule' => $schedule,
        ]);
    }

    public function update(string $id, Request $request) {
        $doctor = Doctor::findOrFail($id);
        $user = Auth::user();
        if(!$user?->is_admin || !$doctor->schedules()->exists()) {
            return response()->json([
                'message' => 'You cannot edit the schedule for this doctor.',
                'success' => false,
            ]);
        }

        $data = $request->validate([
            'events.*.clinic' => ['required', 'string'],
            'events.*.start' => ['required', 'date'],
            'events.*.end' => ['required', 'date'],
        ]);

        try {
            DB::transaction(static function() use ($doctor, $data) {
                $existingSchedules = $doctor->schedules->keyBy(fn($schedule) => $schedule->day->value);
                $incomingDays = [];

                foreach ($data['events'] as $event) {
                    $start = Carbon::parse($event['start']);
                    $end = Carbon::parse($event['end']);
                    $day = $start->dayOfWeek;

                    $incomingDays[] = $day;

                    $scheduleData = [
                        'clinic' => $event['clinic'],
                        'start' => $start->toTimeString(),
                        'end' => $end->toTimeString(),
                    ];

                    if ($existingSchedules->has($day)) {
                        // Update the existing schedule
                        $existingSchedules[$day]->update($scheduleData);
                    } else {
                        // Create new schedule
                        DoctorSchedule::create([
                            'doctor_id' => $doctor->id,
                            'day' => $day,
                            ...$scheduleData
                        ]);
                    }
                }

                // Delete schedules for days that are no longer present in the new data
                foreach ($existingSchedules as $day => $schedule) {
                    if (!in_array($day, $incomingDays, true)) {
                        $schedule->delete();
                    }
                }
            });
            return response()->json([
                'success' => true,
            ]);
        } catch(\Throwable $e) {
            return response()->json([
                'message' => $e->getTraceAsString(),
                'success' => false,
            ]);
        }

    }


}
