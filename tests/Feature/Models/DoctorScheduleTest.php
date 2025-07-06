<?php

use App\Enums\DayOfWeek;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Support\Carbon;

test('doctor should have schedule',  function () {
    $doctor = Doctor::factory()->create();
    $schedule = DoctorSchedule::factory()->create([
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

test('expect start,end to be date', function () {
    $schedule = DoctorSchedule::factory()->createDoctor()->create([
        'start' => now()->setHour(10)->toTimeString(),
        'end' => now()->setHour(20)->toTimeString(),
    ]);
    expect(
        $schedule->start_at
    )
        ->toBeInstanceOf(Carbon::class)
        ->and($schedule->start_at->hour)->toEqual(10)
    ->and($schedule->end_at)
        ->toBeInstanceOf(Carbon::class)
        ->and( $schedule->end_at->hour)->toEqual(20);
});


test('expect break_start,break_end to be date', function () {
    $schedule = DoctorSchedule::factory()->createDoctor()->create([
        'break_start' => now()->setHour(10)->toTimeString(),
        'break_end' => now()->setHour(11)->toTimeString(),
    ]);
    expect(
        $schedule->break_start_at
    )->toBeInstanceOf(Carbon::class)
        ->and($schedule->break_start_at->hour)->toEqual(10)
        ->and($schedule->break_end_at)
        ->toBeInstanceOf(Carbon::class)
        ->and( $schedule->break_end_at->hour)->toEqual(11);
});


