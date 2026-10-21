<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('reply', $this->route('ticket')) ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:10000'],
            'is_internal' => ['boolean'],
            'attachments' => ['array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,png,jpg,jpeg,txt', 'max:5120'],
        ];
    }

    public function isInternal(): bool
    {
        return $this->boolean('is_internal') && $this->user()?->can('tickets.view-any');
    }
}
