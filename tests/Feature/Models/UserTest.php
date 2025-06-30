<?php

use App\Models\User;

test('with patient does not auto create a patient', function () {
    $user = User::factory()->withPatient()->make();

    $this->assertDatabaseCount('booking_users', 0);
    $this->assertDatabaseCount('patients', 0);
    $this->assertNotNull($user->patient);
});

test('user can be created', function () {
    $user = User::factory()->withPatient()->create();
    $this->assertNotNull($user->patient);
});


test('user has is<role> attributes', function () {
    $user = User::factory()->withPatient()->create();
    $this->assertTrue($user->is_patient);
    $this->assertFalse($user->is_admin);
});


test('user getDashboardLink works', function () {
    $user = User::factory()->withPatient()->create();
    $this->assertEquals(
        route('patient.dashboard'),
        $user->getDashboardLink(),
    );

});
