<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InboundMailRequest extends FormRequest
{
    public function authorize(): bool
    {
        $secret = config('services.inbound_mail.secret');

        return is_string($secret)
            && $secret !== ''
            && hash_equals($secret, (string) $this->header('X-Inbound-Mail-Secret'));
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'from' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:10000'],
        ];
    }
}
