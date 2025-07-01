<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->withPatient([
            'first_name' => 'Firsty',
            'last_name' => 'Russell',
        ])->create([
            'email' => '1@email.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->withAdmin([
            'name' => "ADMIN USER",
        ])->create([
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
        ]);
    }
}
