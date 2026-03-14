<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'posting_date' => 'required|date',
            'currency' => 'required|string|size:3',
            'exchange_rate' => 'nullable|numeric|min:0',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'allocations' => 'nullable|array',
            'allocations.*.invoice_id' => 'required_with:allocations|exists:sales_invoices,id',
            'allocations.*.amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'bank_account_id.required' => 'Please select a bank account.',
            'posting_date.required' => 'Posting date is required.',
            'currency.required' => 'Currency is required.',
            'amount.required' => 'The receipt amount is required.',
            'amount.min' => 'The receipt amount must be at least 0.01.',
            'allocations.*.amount.min' => 'Allocation amounts must be positive.',
        ];
    }
}