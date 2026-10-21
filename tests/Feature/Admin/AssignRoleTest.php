<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admins can list the users', function () {
    $this->actingAs(admin())
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('admins can open the roles form of a user', function () {
    $user = customer();

    $this->actingAs(admin())
        ->get(route('admin.users.roles.edit', $user))
        ->assertOk()
        ->assertSee($user->name);
});

test('only admins can manage roles', function () {
    $this->actingAs(agent())
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('admins can assign roles from the form', function () {
    $user = customer();
    $agentRole = Role::findByName('agent');

    $this->actingAs(admin())
        ->put(route('admin.users.roles.update', $user), [
            'roles' => [(string) $agentRole->id],
        ])
        ->assertRedirect(route('admin.users.index'));

    expect($user->refresh()->getRoleNames()->all())->toBe(['agent']);
});

test('roles must exist', function () {
    $this->actingAs(admin())
        ->put(route('admin.users.roles.update', User::factory()->create()), ['roles' => ['999']])
        ->assertSessionHasErrors('roles.0');
});
