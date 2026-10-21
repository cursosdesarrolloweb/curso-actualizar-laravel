<?php

namespace App\Console\Commands;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Cierra los tickets que llevan días esperando una respuesta del cliente')]
#[Signature('tickets:close-stale {--days=7 : Días sin respuesta del cliente}')]
class CloseStaleTickets extends Command
{
    public function handle(): int
    {
        $stale = Ticket::query()
            ->where('status', TicketStatus::Pending)
            ->where('updated_at', '<', now()->subDays((int) $this->option('days')))
            ->get();

        $stale->each->close();

        $this->components->info("Tickets cerrados: {$stale->count()}");

        return self::SUCCESS;
    }
}
