<?php

namespace App\Support;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TicketStats
{
    /**
     * @return array<string, int>
     */
    public function forUser(User $user): array
    {
        return Cache::remember("ticket-stats.{$user->id}", now()->addMinute(), fn (): array => Ticket::query()
            ->forUser($user)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn (mixed $total): int => (int) $total)
            ->all());
    }
}
