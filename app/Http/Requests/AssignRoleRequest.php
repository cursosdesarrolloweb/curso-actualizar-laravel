<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('roles.assign') ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }

    /**
     * @return array<int, string>
     */
    public function roleIds(): array
    {
        return $this->validated('roles', []);
    }
}
