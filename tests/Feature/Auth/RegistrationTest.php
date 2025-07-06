<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Register')
        );
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'first_name' => 'Test User',
        'last_name' => 'Test LastName',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'mobile' => '09493203939'
    ]);
    $this->assertAuthenticated();
    $response->assertRedirect(route('patient.book'));
});

test('new users have patient', function () {
    $response = $this->post('/register', [
        'first_name' => 'Test User',
        'last_name' => 'Test LastName',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'mobile' => '09493203939'
    ]);

    $user = User::whereEmail('test@example.com')->first();
    $this->assertNotNull($user);
    $this->assertNotNull($user->patient_id);
    $this->assertNotNull($user->patient);

});
