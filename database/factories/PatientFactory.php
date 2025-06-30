<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->firstName,
            'last_name' => $this->faker->lastName,
            'birthdate' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Divorced']),
            'height' => $this->faker->optional()->randomFloat(2, 1.4, 2.1), // in meters
            'weight' => $this->faker->optional()->randomFloat(2, 40, 150), // in kg
            'mobile' => $this->faker->phoneNumber,
            'telephone' => $this->faker->optional()->phoneNumber,
            'type_of_transaction' => 'cash',
            'card_number' => $this->faker->optional()->creditCardNumber,
            'address' => $this->faker->optional()->address,
            'notes' => $this->faker->optional()->sentence,
            'occupation' => $this->faker->optional()->jobTitle,
            'company_name' => $this->faker->optional()->company,
            'guardian_name' => $this->faker->optional()->name,
            'relationship' => $this->faker->optional()->randomElement(['Father', 'Mother', 'Sibling', 'Spouse', 'Guardian']),
            'guardian_address' => $this->faker->optional()->address,
            'referring_physician' => $this->faker->optional()->name,
            'primary_care_physician' => $this->faker->optional()->name,
            'referring_institution' => $this->faker->optional()->company,
            'pastmedical' => $this->faker->optional()->text(50),
            'medical' => $this->faker->optional()->text(50),
            'vaccination' => $this->faker->optional()->text(50),
            'drug' => $this->faker->optional()->word,
            'dosage' => $this->faker->optional()->randomElement(['Once a day', 'Twice a day', 'Every 8 hours']),
            'obgyn' => $this->faker->optional()->text(50),
            'others' => $this->faker->optional()->sentence,
        ];
    }
}
