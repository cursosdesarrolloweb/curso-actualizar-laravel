<?php

beforeEach(function () {
    // The CSRF middleware skips its check while running unit tests.
    $this->app['env'] = 'local';
});

test('forms without a csrf token are rejected', function () {
    $this->actingAs(customer())
        ->post(route('tickets.store'), [
            'subject' => 'Sin token',
            'body' => 'Sin token',
            'priority' => 'normal',
        ])
        ->assertStatus(419);
});

test('the inbound mail webhook does not need a csrf token', function () {
    config(['services.inbound_mail.secret' => 'inbound-secret']);

    $this->post(route('webhooks.inbound-mail'), [
        'from' => customer()->email,
        'subject' => 'Desde el correo',
        'text' => 'Hola',
    ], ['X-Inbound-Mail-Secret' => 'inbound-secret'])->assertAccepted();
});
