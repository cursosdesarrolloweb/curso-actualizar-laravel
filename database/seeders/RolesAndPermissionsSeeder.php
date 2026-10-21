<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $viewAnyTicket = Permission::firstOrCreate(['name' => 'tickets.view-any', 'guard_name' => 'web']);
        $assignRoles = Permission::firstOrCreate(['name' => 'roles.assign', 'guard_name' => 'web']);

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'])->syncPermissions([$viewAnyTicket, $assignRoles]);
        Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web'])->syncPermissions([$viewAnyTicket]);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    }
}
