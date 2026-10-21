<?php

use App\Enums\TicketPriority;
use App\Support\Sla;
use Illuminate\Support\Carbon;

beforeEach(fn () => Carbon::setTestNow('2024-01-15 10:00:00'));

afterEach(fn () => Carbon::setTestNow());

test('the due date depends on the priority', function (?TicketPriority $priority, string $dueAt) {
    $sla = new Sla(Carbon::now());

    expect($sla->dueAt($priority)->toDateTimeString())->toBe($dueAt);
})->with([
    'high' => [TicketPriority::High, '2024-01-15 18:00:00'],
    'normal' => [TicketPriority::Normal, '2024-01-16 10:00:00'],
    'low' => [TicketPriority::Low, '2024-01-18 10:00:00'],
    'unclassified' => [null, '2024-01-16 10:00:00'],
]);

test('hours left only count whole hours', function () {
    $sla = new Sla(Carbon::now());

    expect($sla->hoursLeft(Carbon::parse('2024-01-15 11:30:00')))->toBe(1);
});

test('hours left are negative once the due date has passed', function () {
    $sla = new Sla(Carbon::now());

    expect($sla->hoursLeft(Carbon::parse('2024-01-15 07:00:00')))->toBe(-3);
});

test('the sla is breached after the due date', function () {
    $sla = new Sla(Carbon::now());

    expect($sla->isBreached(Carbon::parse('2024-01-15 09:59:00')))->toBeTrue()
        ->and($sla->isBreached(Carbon::parse('2024-01-15 10:01:00')))->toBeFalse();
});

test('it uses the current time when no date is given', function () {
    expect((new Sla)->hoursLeft(Carbon::parse('2024-01-15 15:00:00')))->toBe(5);
});
