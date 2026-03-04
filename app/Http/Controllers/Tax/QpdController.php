<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\QpdService;
use App\Models\Tax\QpdPayment;
use Illuminate\Http\Request;

class QpdController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new QpdService(company_id());
    }

    /**
     * Display QPD forecast
     */
    public function index(Request $request)
    {
        $taxYear = $request->get('tax_year', now()->year);
        
        try {
            $forecast = $this->service->forecast($taxYear);
            
            $payments = QpdPayment::where('company_id', company_id())
                ->where('tax_year', $taxYear)
                ->orderBy('quarter')
                ->get();

            return view('modules.tax.qpd.index', compact('forecast', 'payments', 'taxYear'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load forecast: ' . $e->getMessage());
        }
    }

    /**
     * Show form to create QPD payment
     */
    public function create(Request $request)
    {
        $taxYear = $request->get('tax_year', now()->year);
        $quarter = $request->get('quarter', ceil(now()->month / 3));

        try {
            $calculation = $this->service->calculate($taxYear, $quarter);
            return view('modules.tax.qpd.create', compact('calculation', 'taxYear', 'quarter'));
        } catch (\Exception $e) {
            return back()->with('error', 'Calculation failed: ' . $e->getMessage());
        }
    }

    /**
     * Store QPD payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_year' => 'required|integer',
            'quarter' => 'required|integer|between:1,4',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'payment_method' => 'required|in:BANK,ECOCASH,CASH,TRANSFER',
            'reference' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,submit',
        ]);

        try {
            // Recalculate to ensure accuracy
            $calculation = $this->service->calculate($validated['tax_year'], $validated['quarter']);
            
            $data = array_merge($validated, [
                'estimated_annual_tax' => $calculation['estimated_annual_tax'],
                'percentage_applied' => $calculation['qpd_percentage'],
                'payment_date' => now()->toDateString(),
            ]);

            $payment = $this->service->savePayment($data, auth()->id());

            if ($request->action === 'submit') {
                $payment->update(['status' => 'SUBMITTED']);
                $message = 'QPD payment submitted successfully';
            } else {
                $message = 'QPD payment saved as draft';
            }

            return redirect()->route('tax.qpd.show', $payment)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show QPD payment details
     */
    public function show(QpdPayment $payment)
    {
        abort_unless($payment->company_id === company_id(), 403);

        return view('modules.tax.qpd.show', compact('payment'));
    }

    /**
     * Submit payment
     */
    public function submit(QpdPayment $payment)
    {
        abort_unless($payment->company_id === company_id(), 403);

        if ($payment->status !== 'DRAFT') {
            return back()->with('error', 'Only draft payments can be submitted');
        }

        $payment->update([
            'status' => 'SUBMITTED',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'QPD payment submitted successfully');
    }

    /**
     * Mark payment as paid (with journal entry)
     */
    public function markAsPaid(QpdPayment $payment)
    {
        abort_unless($payment->company_id === company_id(), 403);

        // This would create a journal entry and mark as paid
        // You'll implement this with your PaymentService

        $payment->update(['status' => 'PAID']);

        return back()->with('success', 'QPD payment marked as paid');
    }

    /**
     * Download PDF (ITF12B)
     */
    public function downloadPdf(QpdPayment $payment)
    {
        abort_unless($payment->company_id === company_id(), 403);

        return $this->service->generateItf12bPdf($payment);
    }

    /**
     * Download forecast CSV
     */
    public function downloadForecastCsv(Request $request)
    {
        $taxYear = $request->get('tax_year', now()->year);
        
        $forecast = $this->service->forecast($taxYear);
        $path = $this->service->exportForecastToCsv($forecast);
        
        return response()->download(storage_path("app/public/{$path}"))
            ->deleteFileAfterSend(true);
    }

    /**
     * Print payment
     */
    public function print(QpdPayment $payment)
    {
        abort_unless($payment->company_id === company_id(), 403);

        return view('modules.tax.print.itf12b', [
            'payment' => $payment,
            'company' => $payment->company,
            'print' => true,
        ]);
    }
}