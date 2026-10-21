<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketReply;
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

        $agent = User::factory()->withRole('agent')->create([
            'name' => 'Agente',
            'email' => 'agent@tickets.test',
        ]);

        $customer = User::factory()->withRole('customer')->create([
            'name' => 'Cliente',
            'email' => 'customer@tickets.test',
        ]);

        Ticket::factory()->count(3)->for($customer)->create();

        Ticket::factory()
            ->count(2)
            ->pending()
            ->for($customer)
            ->has(TicketReply::factory()->for($agent), 'replies')
            ->create();

        Ticket::factory()->closed()->for($customer)->create();
    }
}
