<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $date = $this->faker->date();
        $startTime = $this->faker->time();
        $endTime = date('H:i:s', strtotime($startTime . ' +1 hour'));

        return [
            'appointment_number' => strtoupper(Str::random(10)),
            'clinic' => $this->faker->randomElement(['Dental', 'General Medicine', 'Pediatrics']),
            'appointment_for' => $this->faker->name,
            'appointment_date' => $date,
            'appointment_start' => $startTime,
            'appointment_end' => $endTime,
        ];
    }

    public function withPatient(array $attributes = []) : static {
        return $this->state(fn (array $attributes) => [
            'patient_id' => Patient::factory()->create()->id,
        ]);
    }


}
