<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\TicketPriority;
use Illuminate\Support\Carbon;

class Sla
{
    public function __construct(private ?Carbon $now = null)
    {
    }

    public function dueAt(?TicketPriority $priority): Carbon
    {
        return $this->now()->addHours(($priority ?? TicketPriority::Normal)->slaHours());
    }

    public function hoursLeft(Carbon $dueAt): int
    {
        return $this->now()->diffInHours($dueAt, false);
    }

    public function isBreached(Carbon $dueAt): bool
    {
        return $this->now()->greaterThan($dueAt);
    }

    private function now(): Carbon
    {
        return $this->now?->copy() ?? Carbon::now();
    }
}
