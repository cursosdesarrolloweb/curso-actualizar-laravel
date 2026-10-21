<?php

use App\Enums\TicketStatus;
use App\Models\Ticket;

test('an agent reply leaves the ticket waiting for the customer', function () {
    $ticket = Ticket::factory()->for(customer())->create();

    $this->actingAs(agent())
        ->post(route('tickets.replies.store', $ticket), ['body' => '¿Qué navegador usas?'])
        ->assertRedirect(route('tickets.show', $ticket));

    expect($ticket->refresh()->status)->toBe(TicketStatus::Pending)
        ->and($ticket->replies()->sole()->body)->toBe('¿Qué navegador usas?');
});

test('a customer reply opens the ticket again', function () {
    $ticket = Ticket::factory()->pending()->for($customer = customer())->create();

    $this->actingAs($customer)
        ->post(route('tickets.replies.store', $ticket), ['body' => 'Firefox.']);

    expect($ticket->refresh()->status)->toBe(TicketStatus::Open);
});

test('internal notes do not change the status', function () {
    $ticket = Ticket::factory()->for(customer())->create();

    $this->actingAs(agent())
        ->post(route('tickets.replies.store', $ticket), ['body' => 'Revisar con facturación.', 'is_internal' => '1']);

    expect($ticket->refresh()->status)->toBe(TicketStatus::Open)
        ->and($ticket->replies()->sole()->is_internal)->toBeTrue();
});

test('customers cannot write internal notes', function () {
    $ticket = Ticket::factory()->for($customer = customer())->create();

    $this->actingAs($customer)
        ->post(route('tickets.replies.store', $ticket), ['body' => 'Hola', 'is_internal' => '1']);

    expect($ticket->replies()->sole()->is_internal)->toBeFalse();
});

test('closed tickets cannot be replied', function () {
    $ticket = Ticket::factory()->closed()->for($customer = customer())->create();

    $this->actingAs($customer)
        ->from(route('tickets.show', $ticket))
        ->post(route('tickets.replies.store', $ticket), ['body' => 'Sigue fallando.'])
        ->assertRedirect(route('tickets.show', $ticket))
        ->assertSessionHasErrors('body');

    expect($ticket->replies()->count())->toBe(0);
});

test('customers cannot reply to tickets from other customers', function () {
    $ticket = Ticket::factory()->for(customer())->create();

    $this->actingAs(customer())
        ->post(route('tickets.replies.store', $ticket), ['body' => 'Hola'])
        ->assertForbidden();
});
