<?php

use App\Models\Ticket;

beforeEach(fn () => config(['services.inbound_mail.secret' => 'inbound-secret']));

test('an email from a customer opens an unclassified ticket', function () {
    $customer = customer();

    $this->postJson(route('webhooks.inbound-mail'), [
        'from' => $customer->email,
        'subject' => 'No puedo entrar',
        'text' => 'Me dice que la contraseña no es válida.',
    ], ['X-Inbound-Mail-Secret' => 'inbound-secret'])->assertAccepted();

    $ticket = Ticket::sole();

    expect($ticket->user->is($customer))->toBeTrue()
        ->and($ticket->priority)->toBeNull();
});

test('emails from unknown senders are ignored', function () {
    $this->postJson(route('webhooks.inbound-mail'), [
        'from' => 'unknown@example.com',
        'subject' => 'Hola',
        'text' => 'Hola',
    ], ['X-Inbound-Mail-Secret' => 'inbound-secret'])->assertAccepted();

    expect(Ticket::count())->toBe(0);
});

test('the webhook needs the shared secret', function () {
    $this->postJson(route('webhooks.inbound-mail'), [
        'from' => customer()->email,
        'subject' => 'Hola',
        'text' => 'Hola',
    ], ['X-Inbound-Mail-Secret' => 'wrong'])->assertForbidden();
});
