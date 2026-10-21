<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Support\TicketStats;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, TicketStats $stats): View
    {
        return view('dashboard', [
            'statuses' => TicketStatus::cases(),
            'stats' => $stats->forUser($request->user()),
        ]);
    }
}
