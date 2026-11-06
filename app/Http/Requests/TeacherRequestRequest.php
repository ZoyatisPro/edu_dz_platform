<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'motivation' => ['required', 'string', 'min:20', 'max:2000'],
            'subjects' => ['nullable', 'string', 'max:255'],
        ];
    }
}
