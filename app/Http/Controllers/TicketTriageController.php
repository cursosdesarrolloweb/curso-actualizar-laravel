<?php

namespace App\Http\Controllers;

use App\Http\Requests\TriageTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class TicketTriageController extends Controller
{
    public function update(TriageTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update($request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('status', 'Ticket clasificado.');
    }
}
