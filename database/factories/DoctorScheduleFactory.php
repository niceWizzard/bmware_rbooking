<?php

namespace Database\Factories;

use App\Enums\DayOfWeek;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorSchedule>
 */
class DoctorScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::createFromTime(...explode(':', $this->faker->time()));
        $break = $this->faker->optional()->time();
        return [
//            'doctor_id',
            'start' => $start->toTimeString(),
            'end' => $start->copy()->addHour()->toTimeString(),
            'day' => $this->faker->randomElement(DayOfWeek::cases()),
            'break_start' => $break ? Carbon::createFromTime(...explode(':', $this->faker->time()))->toTimeString() : null,
            'break_end' => $break ? Carbon::createFromTime(...explode(':', $this->faker->time()))->addHour()->toTimeString() : null,
        ];
    }

    public function createDoctor(array $attributes = []): static {
        return $this->state(fn (array $attributes) => [
            'doctor_id' => Doctor::factory()->create()->id,
        ]);
    }

}
