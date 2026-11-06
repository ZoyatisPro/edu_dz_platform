<?php

namespace App\Http\Requests;

use App\Support\UserMode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'roles' => ['required', 'array'],
            'roles.*' => ['string', 'distinct', Rule::in(UserMode::ALL)],
        ];
    }
}
