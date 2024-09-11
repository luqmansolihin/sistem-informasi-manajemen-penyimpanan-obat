<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
        ];
    }
}
