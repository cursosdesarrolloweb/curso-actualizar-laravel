<?php

namespace App\Http\Controllers;

use App\Actions\ReplyToTicket;
use App\Http\Requests\StoreTicketReplyRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class TicketReplyController extends Controller
{
    public function store(StoreTicketReplyRequest $request, Ticket $ticket, ReplyToTicket $replyToTicket): RedirectResponse
    {
        $replyToTicket->handle(
            $ticket,
            $request->user(),
            $request->validated('body'),
            $request->isInternal(),
            $request->file('attachments', []),
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('status', 'Respuesta enviada.');
    }
}
