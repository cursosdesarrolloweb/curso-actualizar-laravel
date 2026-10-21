<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTicketRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'priority' => ['required', new Enum(TicketPriority::class)],
            'attachments' => ['array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,png,jpg,jpeg,txt', 'max:5120'],
        ];
    }

    public function priority(): TicketPriority
    {
        return TicketPriority::from($this->validated('priority'));
    }
}
