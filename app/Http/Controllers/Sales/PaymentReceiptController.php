<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\StorePaymentReceiptRequest;
use App\Models\{Payment, Customer, BankAccount, SalesInvoice};
use App\Services\Sales\PaymentPostingService;

class PaymentReceiptController extends Controller
{
    public function index()
    {
        $receipts = Payment::query()
            ->where('company_id', company_id())
            ->where('payment_type', 'RECEIPT')
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('modules.sales.receipts.index', compact('receipts'));
    }

    public function create()
    {
        $companyId = company_id();
        $customers = Customer::query()->where('company_id', $companyId)->where('is_active', 1)->orderBy('name')->get();
        $banks = BankAccount::query()->where('company_id', $companyId)->where('is_active', 1)->orderBy('name')->get();

        $openInvoices = SalesInvoice::query()
            ->where('company_id', $companyId)
            ->where('status', 'SUBMITTED')
            ->orderByDesc('posting_date')
            ->limit(50)
            ->get();

        return view('modules.sales.receipts.create', compact('customers', 'banks', 'openInvoices'));
    }

    public function store(StorePaymentReceiptRequest $request, PaymentPostingService $svc)
    {
        $companyId = company_id();
        
        $data = $request->validated();
        if (isset($data['allocations'])) {
            $data['allocations'] = array_filter($data['allocations'], function($alloc) {
                return !empty($alloc['amount']) && (float)$alloc['amount'] > 0;
            });
        }

        $payment = $svc->createReceipt($data, $companyId, (int)auth()->id());

        return redirect()->route('modules.sales.receipts.show', $payment)
            ->with('ok', 'Receipt created and posted.');
    }

    public function show(Payment $payment)
    {
        abort_unless($payment->company_id === company_id(), 404);
        
        // Load allocations
        $payment->load('allocations');
        
        // Get all sales invoice IDs from allocations
        $invoiceIds = $payment->allocations
            ->where('reference_type', 'SALES_INVOICE')
            ->pluck('reference_id')
            ->filter()
            ->toArray();
        
        // Load the invoices if there are any
        $invoices = [];
        if (!empty($invoiceIds)) {
            $invoices = SalesInvoice::whereIn('id', $invoiceIds)
                ->get()
                ->keyBy('id');
        }
        
        return view('modules.sales.receipts.show', compact('payment', 'invoices'));
    }
}