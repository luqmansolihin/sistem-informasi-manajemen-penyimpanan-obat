<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionPatientUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'patient' => [
                'required',
                'int',
                Rule::exists('patients', 'id')
            ],
            'patient_id' => [
                'required',
                'int',
                Rule::exists('patients', 'id')
            ],
            'disease_name' => ['required', 'string'],
            'checkup_date' => ['required', 'date', 'date_format:Y-m-d'],
            'medical_expense' => ['required', 'decimal:0,2'],
            'transaction_patient' => ['required', 'array'],
            'transaction_patient.*.medicine' => [
                'required',
                'int',
                'distinct',
                Rule::exists('medicines', 'id')
            ],
            'transaction_patient.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_patient.*.medicine.required' => 'The Medicine field is required.',
            'transaction_patient.*.medicine.exists' => 'The Medicine field does not exist.',
            'transaction_patient.*.medicine.integer' => 'The Medicine field must be an integer.',
            'transaction_patient.*.medicine.distinct' => 'The Medicine field is duplicate.',
            'transaction_patient.*.quantity.required' => 'The Quantity field is required.',
            'transaction_patient.*.quantity.integer' => 'The Quantity field must be an integer.',
            'transaction_patient.*.quantity.min' => 'The Quantity field must be at least :min.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'patient_id' => $this->patient
        ]);
    }
}
