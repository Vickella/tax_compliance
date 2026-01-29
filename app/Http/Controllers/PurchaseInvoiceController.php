<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseInvoiceRequest;
use App\Models\Currency;
use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceLine;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = PurchaseInvoice::query()
            ->with('supplier')
            ->when($request->filled('supplier'), function ($query) use ($request) {
                $query->whereHas('supplier', function ($supplierQuery) use ($request) {
                    $supplierQuery->where('name', 'like', '%'.$request->input('supplier').'%');
                });
            })
            ->orderByDesc('posting_date')
            ->paginate(15);

        return view('purchases.invoices.index', [
            'invoices' => $invoices,
        ]);
        return view('purchases.invoices.index');
    }

    public function create(Request $request)
    {
        return view('purchases.invoices.create', [
            'suppliers' => Supplier::query()->orderBy('name')->get(),
            'items' => Item::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'currencies' => Currency::query()->orderBy('code')->get(),
            'vatRate' => config('tax.vat_rate'),
        ]);
    }

    public function store(PurchaseInvoiceRequest $request)
    {
        $validated = $request->validated();
        $companyId = $request->user()?->company_id;

        return DB::transaction(function () use ($validated, $companyId) {
            $supplier = Supplier::findOrFail($validated['supplier_id']);
            $lineItems = $validated['lines'];

            $subtotal = 0.0;
            $vatAmount = 0.0;

            $invoice = PurchaseInvoice::create([
                'company_id' => $companyId,
                'invoice_no' => $validated['invoice_no'],
                'supplier_id' => $validated['supplier_id'],
                'supplier_invoice_no' => $validated['supplier_invoice_no'] ?? null,
                'supplier_vat_number' => $supplier->vat_number,
                'supplier_tin' => $supplier->tin,
                'input_tax_document_ref' => $validated['input_tax_document_ref'] ?? null,
                'bill_of_entry_ref' => $validated['bill_of_entry_ref'] ?? null,
                'posting_date' => $validated['posting_date'],
                'due_date' => $validated['due_date'] ?? null,
                'currency' => $validated['currency'],
                'exchange_rate' => $validated['exchange_rate'],
                'status' => 'DRAFT',
                'remarks' => $validated['remarks'] ?? null,
            ]);

            foreach ($lineItems as $line) {
                $amount = $line['qty'] * $line['rate'];
                $lineVat = $amount * ($line['vat_rate'] ?? 0);
                $subtotal += $amount;
                $vatAmount += $lineVat;

                PurchaseInvoiceLine::create([
                    'purchase_invoice_id' => $invoice->id,
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
                ->route('purchases.invoices.index')
                ->with('status', "Purchase invoice {$invoice->invoice_no} created.");
        });
    }
}
        return view('purchases.invoices.create');
    }

}
