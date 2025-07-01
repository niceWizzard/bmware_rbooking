<?php

use App\Models\Admin;
use App\Models\User;

test('admin model has user property', function () {
    $admin = Admin::factory()->create();
    $user = User::factory()->create([
        'admin_id' => $admin->id
    ]);
    expect($user->admin->user)
        ->toBeInstanceOf(User::class)
        ->not()
        ->toBeNull()
        ->and($user->id)
        ->toEqual($user->admin->user->id);
});
