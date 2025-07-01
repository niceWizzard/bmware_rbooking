<?php

use App\Models\Patient;
use App\Models\User;
use Database\Seeders\UserSeeder;


test('patient model has user', function () {
    $patient = Patient::factory()->create();
    $user = User::factory()->create([
        'patient_id' => $patient->id,
    ]);
    $user->refresh();
    $patient->refresh();
    $this->assertNotNull($patient->user);
    $this->assertEquals($user->id, $patient->user->id);
});
