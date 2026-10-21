<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TriageTicketRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'priority' => ['nullable', new Enum(TicketPriority::class)],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}
