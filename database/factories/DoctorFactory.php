<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->name();
        return [
            'name' => $name,
            'code' => self::generateCodeFromName($name),
            'clinic' => $this->faker->randomElement(['Alpha Med', 'Mt. Carmel']),
            'specialty' => $this->faker->randomElement(['Neurologist', 'Cardiologist', 'Pediatrician']),
        ];
    }

    private static function generateCodeFromName(string $fullName): string
    {
        $parts = explode(' ', trim($fullName));
        if (count($parts) < 2) {
            return strtoupper(substr($fullName, 0, 6)); // fallback if not enough parts
        }
        $first = strtoupper(substr($parts[0], 0, 3)); // first 3 letters of first name
        $last = strtoupper(substr($parts[1], 0, 3));  // first 3 letters of last name
        return $first . $last;
    }

}
