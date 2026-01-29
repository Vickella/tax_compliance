<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesInvoiceRequest;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceLine;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = SalesInvoice::query()
            ->with('customer')
            ->when($request->filled('customer'), function ($query) use ($request) {
                $query->whereHas('customer', function ($customerQuery) use ($request) {
                    $customerQuery->where('name', 'like', '%'.$request->input('customer').'%');
                });
            })
            ->orderByDesc('posting_date')
            ->paginate(15);

        return view('sales.invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(Request $request)
    {
        return view('sales.invoices.create', [
            'customers' => Customer::query()->orderBy('name')->get(),
            'items' => Item::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'currencies' => Currency::query()->orderBy('code')->get(),
            'vatRate' => config('tax.vat_rate'),
        ]);
    }

    public function store(SalesInvoiceRequest $request)
    {
        $validated = $request->validated();
        $companyId = $request->user()?->company_id;

        return DB::transaction(function () use ($validated, $companyId) {
            $customer = Customer::findOrFail($validated['customer_id']);
            $lineItems = $validated['lines'];

            $subtotal = 0.0;
            $vatAmount = 0.0;

            $invoice = SalesInvoice::create([
                'company_id' => $companyId,
                'invoice_no' => $validated['invoice_no'],
                'invoice_type' => $validated['invoice_type'],
                'customer_id' => $validated['customer_id'],
                'posting_date' => $validated['posting_date'],
                'due_date' => $validated['due_date'] ?? null,
                'currency' => $validated['currency'],
                'exchange_rate' => $validated['exchange_rate'],
                'status' => 'DRAFT',
                'customer_tin' => $customer->tin,
                'customer_vat_number' => $customer->vat_number,
                'vat_category' => $validated['vat_category'] ?? null,
                'fiscal_device_serial' => $validated['fiscal_device_serial'] ?? null,
                'fiscal_invoice_number' => $validated['fiscal_invoice_number'] ?? null,
                'qr_payload' => $validated['qr_payload'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            foreach ($lineItems as $line) {
                $amount = $line['qty'] * $line['rate'];
                $lineVat = $amount * ($line['vat_rate'] ?? 0);
                $subtotal += $amount;
                $vatAmount += $lineVat;

                SalesInvoiceLine::create([
                    'sales_invoice_id' => $invoice->id,
                    'item_id' => $line['item_id'],
                    'description' => $line['description'] ?? null,
                    'warehouse_id' => $line['warehouse_id'] ?? null,
                    'qty' => $line['qty'],
                    'rate' => $line['rate'],
                    'amount' => $amount,
                    'vat_rate' => $line['vat_rate'] ?? 0,
                    'vat_amount' => $lineVat,
                ]);
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'total' => $subtotal + $vatAmount,
            ]);

            return redirect()
                ->route('sales.invoices.index')
                ->with('status', "Invoice {$invoice->invoice_no} created.");
        });
    }
}
