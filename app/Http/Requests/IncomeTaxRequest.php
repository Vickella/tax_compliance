<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tax_year' => ['required', 'integer'],
            'taxable_income' => ['required', 'numeric', 'min:0'],
            'non_deductible_expenses' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
