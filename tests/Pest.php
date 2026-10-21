<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Feature tests run against the "testing" database of Sail, with the roles
| and permissions seeded before each test.
|
*/

uses(TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        $this->seed(RolesAndPermissionsSeeder::class);
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function customer(): User
{
    return User::factory()->withRole('customer')->create();
}

function agent(): User
{
    return User::factory()->withRole('agent')->create();
}

function admin(): User
{
    return User::factory()->withRole('admin')->create();
}
