<?php

use App\Models\Ticket;
use App\Models\User;

function withApiToken(User $user): array
{
    return ['Authorization' => 'Bearer '.$user->createToken('test')->plainTextToken];
}

test('the api needs a token', function () {
    $this->getJson(route('api.tickets.index'))->assertUnauthorized();
});

test('customers only get their own tickets', function () {
    $customer = customer();
    $own = Ticket::factory()->for($customer)->create();
    Ticket::factory()->for(customer())->create();

    $this->getJson(route('api.tickets.index'), withApiToken($customer))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $own->id);
});

test('customers can open tickets from the api', function () {
    $this->freezeSecond();

    $this->postJson(route('api.tickets.store'), [
        'subject' => 'La app se cierra al abrir un ticket',
        'body' => 'Pasa desde la última versión.',
        'priority' => 'normal',
    ], withApiToken(customer()))
        ->assertCreated()
        ->assertJsonPath('status', 'open')
        ->assertJsonPath('hours_left', 24);
});

test('customers cannot read tickets from other customers', function () {
    $ticket = Ticket::factory()->for(customer())->create();

    $this->getJson(route('api.tickets.show', $ticket), withApiToken(customer()))
        ->assertForbidden();
});
