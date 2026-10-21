<?php

use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::disk('local')->put('attachments/factura.pdf', 'PDF');
});

test('the owner of the ticket can download its attachments', function () {
    $ticket = Ticket::factory()->for($customer = customer())->create();
    $attachment = Attachment::factory()->for($ticket)->create([
        'path' => 'attachments/factura.pdf',
        'original_name' => 'factura.pdf',
    ]);

    $this->actingAs($customer)
        ->get(route('attachments.show', $attachment))
        ->assertOk()
        ->assertDownload('factura.pdf');
});

test('other customers cannot download the attachments', function () {
    $attachment = Attachment::factory()->for(Ticket::factory()->for(customer()))->create([
        'path' => 'attachments/factura.pdf',
    ]);

    $this->actingAs(customer())
        ->get(route('attachments.show', $attachment))
        ->assertForbidden();
});
