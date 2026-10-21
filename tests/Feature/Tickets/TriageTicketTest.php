<?php

use App\Enums\TicketPriority;
use App\Models\Ticket;

test('agents can set the priority and the estimated hours', function () {
    $ticket = Ticket::factory()->for(customer())->create(['priority' => null]);

    $this->actingAs(agent())
        ->put(route('tickets.triage.update', $ticket), ['priority' => 'low', 'estimated_hours' => '2.5'])
        ->assertRedirect(route('tickets.show', $ticket));

    $ticket->refresh();

    expect($ticket->priority)->toBe(TicketPriority::Low)
        ->and($ticket->estimated_hours)->toBe(2.5);
});

test('customers cannot triage tickets', function () {
    $ticket = Ticket::factory()->for($customer = customer())->create();

    $this->actingAs($customer)
        ->put(route('tickets.triage.update', $ticket), ['priority' => 'high'])
        ->assertForbidden();
});
