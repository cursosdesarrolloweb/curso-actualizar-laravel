<?php

namespace App\Actions;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Support\Sla;
use Illuminate\Http\UploadedFile;

class CreateTicket
{
    public function __construct(
        private Sla $sla,
        private StoreAttachments $storeAttachments,
    ) {}

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    public function handle(User $customer, string $subject, string $body, ?TicketPriority $priority, array $attachments = []): Ticket
    {
        $ticket = $customer->tickets()->create([
            'subject' => $subject,
            'body' => $body,
            'status' => TicketStatus::Open,
            'priority' => $priority,
            'due_at' => $this->sla->dueAt($priority),
        ]);

        $this->storeAttachments->handle($ticket, $customer, $attachments);

        return $ticket;
    }
}
