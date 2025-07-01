<?php

use App\Enums\DayOfWeek;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

test('doctor should have schedule',  function () {
    $doctor = Doctor::factory()->create();
    $schedule = DoctorSchedule::create([
        'day' => DayOfWeek::Monday,
        'doctor_id' => $doctor->id,
        'start' => now(),
        'end' => now()->addHours(2),
    ]);

    $doctor->refresh();

    expect($schedule->doctor)
        ->toBeInstanceOf(Doctor::class)
        ->not()
        ->toBeNull();
});

