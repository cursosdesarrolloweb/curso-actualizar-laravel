<?php

namespace App\Actions;

use App\Enums\TicketStatus;
use App\Exceptions\TicketAlreadyClosedException;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ReplyToTicket
{
    public function __construct(private StoreAttachments $storeAttachments) {}

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    public function handle(Ticket $ticket, User $author, string $body, bool $internal = false, array $attachments = []): TicketReply
    {
        if ($ticket->isClosed()) {
            throw new TicketAlreadyClosedException('No se puede responder a un ticket cerrado.');
        }

        $reply = $ticket->replies()->make([
            'body' => $body,
            'is_internal' => $internal,
        ]);
        $reply->user()->associate($author);
        $reply->save();

        $this->storeAttachments->handle($ticket, $author, $attachments);

        if (! $internal) {
            $ticket->status = $ticket->user()->is($author) ? TicketStatus::Open : TicketStatus::Pending;
            $ticket->touch();
        }

        return $reply;
    }
}
