<?php

use App\Models\Ticket;
use Illuminate\Support\Str;

test('tickets use uuids as primary key', function () {
    expect(Str::isUuid(Ticket::factory()->create()->id))->toBeTrue();
});

test('the open scope leaves closed tickets out', function () {
    $open = Ticket::factory()->create();
    $pending = Ticket::factory()->pending()->create();
    Ticket::factory()->closed()->create();

    expect(Ticket::open()->pluck('id')->sort()->values()->all())
        ->toBe(collect([$open->id, $pending->id])->sort()->values()->all());
});

test('the for user scope limits customers to their tickets', function () {
    $customer = customer();
    $own = Ticket::factory()->for($customer)->create();
    Ticket::factory()->create();

    expect(Ticket::forUser($customer)->pluck('id')->all())->toBe([$own->id])
        ->and(Ticket::forUser(agent())->count())->toBe(2);
});
