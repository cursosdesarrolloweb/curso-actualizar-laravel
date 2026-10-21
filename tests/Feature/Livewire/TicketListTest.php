<?php

use App\Livewire\TicketList;
use App\Models\Ticket;
use Livewire\Livewire;

test('the tickets page renders the list', function () {
    $this->actingAs(customer())
        ->get(route('tickets.index'))
        ->assertOk()
        ->assertSeeLivewire(TicketList::class);
});

test('customers only see their own tickets', function () {
    $customer = customer();
    Ticket::factory()->for($customer)->create(['subject' => 'La impresora no imprime']);
    Ticket::factory()->for(customer())->create(['subject' => 'Ticket de otro cliente']);

    $this->actingAs($customer);

    Livewire::test(TicketList::class)
        ->assertSee('La impresora no imprime')
        ->assertDontSee('Ticket de otro cliente');
});

test('agents see every ticket', function () {
    Ticket::factory()->for(customer())->create(['subject' => 'La impresora no imprime']);
    Ticket::factory()->for(customer())->create(['subject' => 'No llega el correo']);

    $this->actingAs(agent());

    Livewire::test(TicketList::class)
        ->assertSee('La impresora no imprime')
        ->assertSee('No llega el correo');
});

test('the list can be searched by subject', function () {
    $customer = customer();
    Ticket::factory()->for($customer)->create(['subject' => 'La impresora no imprime']);
    Ticket::factory()->for($customer)->create(['subject' => 'No llega el correo']);

    $this->actingAs($customer);

    Livewire::test(TicketList::class)
        ->set('search', 'impresora')
        ->assertSee('La impresora no imprime')
        ->assertDontSee('No llega el correo');
});

test('the search can come from the query string', function () {
    $customer = customer();
    Ticket::factory()->for($customer)->create(['subject' => 'La impresora no imprime']);
    Ticket::factory()->for($customer)->create(['subject' => 'No llega el correo']);

    $this->actingAs($customer)
        ->get(route('tickets.index', ['q' => 'correo']))
        ->assertSee('No llega el correo')
        ->assertDontSee('La impresora no imprime');
});

test('closed tickets are hidden until asked for', function () {
    $customer = customer();
    Ticket::factory()->closed()->for($customer)->create(['subject' => 'Ya resuelto']);

    $this->actingAs($customer);

    Livewire::test(TicketList::class)
        ->assertDontSee('Ya resuelto')
        ->set('onlyOpen', false)
        ->assertSee('Ya resuelto');
});
