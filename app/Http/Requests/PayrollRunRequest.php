<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period_id' => ['required', 'integer'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'earnings' => ['nullable', 'array'],
            'earnings.basic_salary' => ['nullable', 'numeric', 'min:0'],
            'earnings.housing_allowance' => ['nullable', 'numeric', 'min:0'],
            'earnings.transport_allowance' => ['nullable', 'numeric', 'min:0'],
            'earnings.other_income' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
