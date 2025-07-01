<?php

use App\Models\Appointment;
use Illuminate\Support\Carbon;

test('appointment date attributes are valid date', function () {
    $appointment = Appointment::factory()->withPatient()->create();

    $this->assertInstanceOf(Carbon::class, $appointment->appointment_date);
    $this->assertInstanceOf(Carbon::class, $appointment->appointment_start);
    $this->assertInstanceOf(Carbon::class, $appointment->appointment_end);
});


test('appointment start happens on the same day as appointment date', function () {
    $appointment = Appointment::factory()->withPatient()->create();
    $this->assertEquals(
        $appointment->appointment_date->toDateString(),
        $appointment->appointment_start_date_time->toDateString(),
    );
});

test('appointment end happens on the same day as appointment date', function () {
    $appointment = Appointment::factory()->withPatient()->create();
    $this->assertEquals(
        $appointment->appointment_date->toDateString(),
        $appointment->appointment_end_date_time->toDateString(),
    );
});

test('appointment number is auto set', function () {
    $appointment = Appointment::factory()->withPatient()->create();

    expect($appointment->appointment_number)
        ->not()
        ->toBeNull()
        ->and(
            Appointment
                ::whereAppointmentNumber($appointment->appointment_number)
                ->count()
        )->toBeOne();
});

test('appointment number is somewhat unique', function () {
    $appointments = Appointment::factory(100)->withPatient()->create();
    foreach ($appointments as $appointment) {
        expect(
            Appointment
                ::whereAppointmentNumber($appointment->appointment_number)
                ->count()
        )->toBeOne();
    }
});
