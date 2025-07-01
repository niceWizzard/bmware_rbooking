<?php

use App\Models\User;

test('admin model has user property', function () {
    $user = User::factory()->withAdmin()->create();
    expect($user->admin->user)
        ->toBeInstanceOf(User::class)
        ->toEqual($user->id, $user->admin->user->id)
        ->not()
        ->toBeNull();
});
