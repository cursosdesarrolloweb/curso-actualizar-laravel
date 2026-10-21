<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('the create ticket screen can be rendered', function () {
    $this->actingAs(customer())
        ->get(route('tickets.create'))
        ->assertOk();
});

test('customers can open a ticket', function () {
    $this->freezeSecond();
    $customer = customer();

    $response = $this->actingAs($customer)->post(route('tickets.store'), [
        'subject' => 'No puedo descargar la factura',
        'body' => 'Al pulsar en descargar no pasa nada.',
        'priority' => 'high',
    ]);

    $ticket = Ticket::sole();

    $response->assertRedirect(route('tickets.show', $ticket));
    expect($ticket->user->is($customer))->toBeTrue()
        ->and($ticket->status)->toBe(TicketStatus::Open)
        ->and($ticket->priority)->toBe(TicketPriority::High)
        ->and($ticket->due_at->equalTo(now()->addHours(8)))->toBeTrue();
});

test('attachments are stored on the local disk', function () {
    Storage::fake('local');

    $this->actingAs(customer())->post(route('tickets.store'), [
        'subject' => 'Error al pagar',
        'body' => 'Adjunto la captura.',
        'priority' => 'normal',
        'attachments' => [UploadedFile::fake()->create('error.pdf', 120, 'application/pdf')],
    ]);

    $attachment = Ticket::sole()->attachments()->sole();

    expect($attachment->original_name)->toBe('error.pdf');
    Storage::disk('local')->assertExists($attachment->path);
});

test('a ticket needs a subject, a body and a valid priority', function () {
    $this->actingAs(customer())
        ->post(route('tickets.store'), ['priority' => 'urgent'])
        ->assertSessionHasErrors(['subject', 'body', 'priority']);

    expect(Ticket::count())->toBe(0);
});

test('only some file types can be attached', function () {
    $this->actingAs(customer())
        ->post(route('tickets.store'), [
            'subject' => 'Error al pagar',
            'body' => 'Adjunto un ejecutable.',
            'priority' => 'normal',
            'attachments' => [UploadedFile::fake()->create('virus.exe', 10)],
        ])
        ->assertSessionHasErrors('attachments.0');
});
