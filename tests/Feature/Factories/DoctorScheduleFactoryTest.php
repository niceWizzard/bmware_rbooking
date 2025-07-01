<?php

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\QueryException;

test('expect doctor schedule factory without doctor_id throw error', function () {
    expect(
        static fn () => DoctorSchedule::factory()->create()
    )->toThrow(QueryException::class);
});


test('expect doctor schedule createDoctor to have doctor', function () {
    $schedule = DoctorSchedule::factory()->createDoctor()->create();
    expect($schedule->doctor)->toBeInstanceOf(Doctor::class);
});
