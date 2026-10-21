<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateTicket;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        return Ticket::query()
            ->forUser($request->user())
            ->latest()
            ->paginate(15);
    }

    public function store(StoreTicketRequest $request, CreateTicket $createTicket): JsonResponse
    {
        $ticket = $createTicket->handle(
            $request->user(),
            $request->validated('subject'),
            $request->validated('body'),
            $request->priority(),
            $request->file('attachments', []),
        );

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket): Ticket
    {
        $this->authorize('view', $ticket);

        return $ticket;
    }
}
