<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        User::factory()->withRole('admin')->create([
            'name' => 'Admin',
            'email' => 'admin@tickets.test',
        ]);

        User::factory()->withRole('agent')->create([
            'name' => 'Agente',
            'email' => 'agent@tickets.test',
        ]);

        User::factory()->withRole('customer')->create([
            'name' => 'Cliente',
            'email' => 'customer@tickets.test',
        ]);
    }
}
