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

        $viewAnyTicket = Permission::findOrCreate('tickets.view-any');
        $assignRoles = Permission::findOrCreate('roles.assign');

        Role::findOrCreate('admin')->syncPermissions([$viewAnyTicket, $assignRoles]);
        Role::findOrCreate('agent')->syncPermissions([$viewAnyTicket]);
        Role::findOrCreate('customer');
    }
}
