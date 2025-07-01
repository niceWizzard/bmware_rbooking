<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'patient',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withPatient(array $attributes = []): static
    {
        return $this
            ->afterMaking(function (User $user) use ($attributes) {
                $patient = Patient::factory()->make($attributes);
                $user->setRelation('patient', $patient);
            })
            ->afterCreating(function (User $user) use ($attributes) {
                $patient = Patient::factory()->create($attributes);
                $user->patient_id = $patient->id;
                $user->save();
            });
    }

    public function withAdmin(array $attributes = []): static
    {
        return $this->state(fn (array $attributes) => [
                'role' => User::ROLE_ADMIN,
            ])
            ->afterMaking(function (User $user) use ($attributes) {
                $admin = Admin::factory()->make($attributes);
                $user->setRelation('admin', $admin);
            })
            ->afterCreating(function (User $user) use ($attributes) {
                $admin = Admin::factory()->create($attributes);
                $user->admin_id = $admin->id;
                $user->save();
            });
    }


}
