<?php

use App\Models\Ticket;
use App\Models\TicketReply;

test('customers can see their own tickets', function () {
    $ticket = Ticket::factory()->for($customer = customer())->create();

    $this->actingAs($customer)
        ->get(route('tickets.show', $ticket))
        ->assertOk()
        ->assertSee($ticket->subject);
});

test('customers cannot see tickets from other customers', function () {
    $ticket = Ticket::factory()->for(customer())->create();

    $this->actingAs(customer())
        ->get(route('tickets.show', $ticket))
        ->assertForbidden();
});

test('agents can see any ticket', function () {
    $ticket = Ticket::factory()->for(customer())->create();

    $this->actingAs(agent())
        ->get(route('tickets.show', $ticket))
        ->assertOk();
});

test('line breaks in the body are rendered and html is escaped', function () {
    $ticket = Ticket::factory()->for($customer = customer())->create([
        'body' => "Primera línea\n<b>Segunda</b> línea",
    ]);

    $this->actingAs($customer)
        ->get(route('tickets.show', $ticket))
        ->assertSeeHtml('Primera línea<br />')
        ->assertSeeHtml('&lt;b&gt;Segunda&lt;/b&gt; línea');
});

test('internal notes are hidden from customers', function () {
    $ticket = Ticket::factory()->for($customer = customer())->create();
    TicketReply::factory()->for($ticket)->internal()->create(['body' => 'El cliente ya lo pidió el mes pasado']);

    $this->actingAs($customer)
        ->get(route('tickets.show', $ticket))
        ->assertDontSee('El cliente ya lo pidió el mes pasado');

    $this->actingAs(agent())
        ->get(route('tickets.show', $ticket))
        ->assertSee('El cliente ya lo pidió el mes pasado');
});
