<?php

use App\Enums\DayOfWeek;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

test('doctor should have schedule',  function () {
    $doctor = Doctor::factory()->create();
    DoctorSchedule::create([
        'day' => DayOfWeek::Monday,
        'doctor_id' => $doctor->id,
        'start' => now(),
        'end' => now()->addHours(2),
        'clinic' => 'Alphamed Diagnostics - San Fernando'
    ]);

    $doctor->refresh();
    expect($doctor->schedules)
        ->toBeIterable()
        ->not()
        ->toBeNull();
});
