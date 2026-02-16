<?php

use App\Models\Role;
use App\Models\User;

test('links index loads without server error for authorized user', function () {
    $role = Role::query()->firstOrCreate(['name' => 'viewer']);

    $user = User::factory()->create();
    $user->roles()->syncWithoutDetaching([$role->id]);

    $response = $this
        ->actingAs($user)
        ->get('/links');

    $response->assertStatus(200);
});
