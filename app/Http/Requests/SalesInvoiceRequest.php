<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_no' => ['required', 'string', 'max:50'],
            'invoice_type' => ['required', 'in:TAX_INVOICE,CREDIT_NOTE,DEBIT_NOTE'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'posting_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'vat_category' => ['nullable', 'string', 'max:30'],
            'fiscal_device_serial' => ['nullable', 'string', 'max:80'],
            'fiscal_invoice_number' => ['nullable', 'string', 'max:80'],
            'qr_payload' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
            'lines.*.warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'lines.*.qty' => ['required', 'numeric', 'min:0.0001'],
            'lines.*.rate' => ['required', 'numeric', 'min:0'],
            'lines.*.vat_rate' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
