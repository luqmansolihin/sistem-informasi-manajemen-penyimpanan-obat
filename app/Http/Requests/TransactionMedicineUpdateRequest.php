<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionMedicineUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'medicine' => [
                'required',
                'int',
                Rule::exists('medicines', 'id')->withoutTrashed()
            ],
            'medicine_id' => [
                'required',
                'int',
                Rule::exists('medicines', 'id')->withoutTrashed()
            ],
            'quantity' => ['required', 'int', 'min:1'],
            'qty' => ['required', 'int', 'min:1'],
            'qty_balance' => ['required', 'int', 'min:1'],
            'purchase_date' => ['required', 'date', 'date_format:Y-m-d'],
            'expired_date' => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'medicine_id' => $this->medicine,
            'qty' => $this->quantity,
            'qty_balance' => $this->quantity
        ]);
    }
}
