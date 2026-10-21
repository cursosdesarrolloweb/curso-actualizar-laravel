<?php

namespace App\Http\Controllers;

use App\Actions\CreateTicket;
use App\Enums\TicketPriority;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        return view('tickets.index', ['search' => (string) $request->input('q', '')]);
    }

    public function create(): View
    {
        return view('tickets.create', ['priorities' => TicketPriority::cases()]);
    }

    public function store(StoreTicketRequest $request, CreateTicket $createTicket): RedirectResponse
    {
        $ticket = $createTicket->handle(
            $request->user(),
            $request->validated('subject'),
            $request->validated('body'),
            $request->priority(),
            $request->file('attachments', []),
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('status', 'Ticket creado.');
    }

    public function show(Request $request, Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $replies = $ticket->replies()
            ->with('user')
            ->unless($request->user()->can('tickets.view-any'), fn (Builder $query) => $query->where('is_internal', false))
            ->oldest()
            ->get();

        return view('tickets.show', [
            'ticket' => $ticket->load(['user', 'attachments']),
            'replies' => $replies,
            'priorities' => TicketPriority::cases(),
        ]);
    }
}
