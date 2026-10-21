<?php

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;

test('tickets waiting for the customer for a week are closed', function () {
    $stale = Ticket::factory()->pending()->create(['updated_at' => now()->subDays(8)]);
    $recent = Ticket::factory()->pending()->create(['updated_at' => now()->subDays(2)]);
    $open = Ticket::factory()->create(['updated_at' => now()->subDays(30)]);

    $this->artisan('tickets:close-stale')
        ->expectsOutputToContain('Tickets cerrados: 1')
        ->assertSuccessful();

    expect($stale->refresh()->status)->toBe(TicketStatus::Closed)
        ->and($stale->closed_at)->not->toBeNull()
        ->and($recent->refresh()->status)->toBe(TicketStatus::Pending)
        ->and($open->refresh()->status)->toBe(TicketStatus::Open);
});

test('the command runs every hour', function () {
    $event = collect(app(Schedule::class)->events())
        ->first(fn (Event $event) => str_contains((string) $event->command, 'tickets:close-stale'));

    expect($event)->not->toBeNull()
        ->and($event->expression)->toBe('0 * * * *');
});
