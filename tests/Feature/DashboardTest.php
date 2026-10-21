<?php

use App\Models\Ticket;

test('the dashboard shows the tickets by status', function () {
    $customer = customer();
    Ticket::factory()->count(2)->for($customer)->create();
    Ticket::factory()->pending()->for($customer)->create();

    $this->actingAs($customer)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Abierto', '2', 'Esperando al cliente', '1', 'Cerrado', '0']);
});
