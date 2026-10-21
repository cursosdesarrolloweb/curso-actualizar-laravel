<?php

namespace App\Http\Controllers;

use App\Actions\CreateTicket;
use App\Http\Requests\InboundMailRequest;
use App\Models\User;
use Illuminate\Http\Response;

class InboundMailController extends Controller
{
    public function __invoke(InboundMailRequest $request, CreateTicket $createTicket): Response
    {
        $customer = User::where('email', $request->validated('from'))->first();

        if ($customer !== null) {
            $createTicket->handle(
                $customer,
                $request->validated('subject'),
                $request->validated('text'),
                null,
            );
        }

        return response()->noContent(202);
    }
}
