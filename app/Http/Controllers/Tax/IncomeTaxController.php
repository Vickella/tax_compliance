<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\IncomeTaxService;
use App\Models\Tax\IncomeTaxReturn;
use Illuminate\Http\Request;

class IncomeTaxController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new IncomeTaxService(company_id());
    }

    /**
     * Display list of income tax returns
     */
    public function index(Request $request)
    {
        $returns = IncomeTaxReturn::where('company_id', company_id())
            ->orderBy('tax_year', 'desc')
            ->paginate(20);

        return view('modules.tax.income.index', compact('returns'));
    }

    /**
     * Show form to create new income tax return
     */
    public function create(Request $request)
    {
        $taxYear = $request->get('tax_year', now()->year);

        try {
            $calculation = $this->service->calculate($taxYear);
            return view('modules.tax.income.create', compact('calculation', 'taxYear'));
        } catch (\Exception $e) {
            return back()->with('error', 'Calculation failed: ' . $e->getMessage());
        }
    }

    /**
     * Store income tax return
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_year' => 'required|integer|min:2000|max:2100',
            'total_income' => 'required|numeric',
            'total_expenses' => 'required|numeric',
            'add_back_amount' => 'required|numeric',
            'taxable_income' => 'required|numeric',
            'income_tax' => 'required|numeric',
            'aids_levy' => 'required|numeric',
            'total_tax' => 'required|numeric',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,submit',
        ]);

        try {
            // Recalculate to ensure accuracy
            $calculation = $this->service->calculate($validated['tax_year']);
            
            // Verify matches user input
            if (abs($calculation['total_tax'] - $validated['total_tax']) > 0.01) {
                return back()->with('error', 'Tax calculation mismatch. Please refresh and try again.')
                    ->withInput();
            }

            $data = array_merge($validated, [
                'income_breakdown' => $calculation['income']['breakdown'],
                'expense_breakdown' => $calculation['expenses']['breakdown'],
                'add_back_breakdown' => $calculation['add_backs']['breakdown'],
                'tax_rate' => $calculation['tax_rate'],
                'assessed_loss_bf' => $calculation['assessed_loss_bf'],
                'assessed_loss_cf' => $calculation['assessed_loss_cf'],
                'qpd_paid' => $calculation['qpd_paid'],
                'balance_due' => $calculation['balance_due'],
                'filing_date' => now()->toDateString(),
            ]);

            $return = $this->service->saveReturn($data, auth()->id());

            if ($request->action === 'submit') {
                $return->update(['status' => 'SUBMITTED']);
                $message = 'Income tax return submitted successfully';
            } else {
                $message = 'Income tax return saved as draft';
            }

            return redirect()->route('tax.income.show', $return)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save return: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show income tax return details
     */
    public function show(IncomeTaxReturn $return)
    {
        abort_unless($return->company_id === company_id(), 403);

        return view('modules.tax.income.show', compact('return'));
    }

    /**
     * Submit return
     */
    public function submit(IncomeTaxReturn $return)
    {
        abort_unless($return->company_id === company_id(), 403);

        if ($return->status !== 'DRAFT') {
            return back()->with('error', 'Only draft returns can be submitted');
        }

        $return->update([
            'status' => 'SUBMITTED',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Income tax return submitted successfully');
    }

    /**
     * Download PDF (ITF12C)
     */
    public function downloadPdf(IncomeTaxReturn $return)
    {
        abort_unless($return->company_id === company_id(), 403);

        return $this->service->generateItf12cPdf($return);
    }

    /**
     * Download CSV
     */
    public function downloadCsv(IncomeTaxReturn $return)
    {
        abort_unless($return->company_id === company_id(), 403);

        $path = $this->service->exportToCsv($return);
        
        return response()->download(storage_path("app/public/{$path}"))
            ->deleteFileAfterSend(true);
    }

    /**
     * Print view
     */
    public function print(IncomeTaxReturn $return)
    {
        abort_unless($return->company_id === company_id(), 403);

        return view('modules.tax.print.itf12c', [
            'return' => $return,
            'company' => $return->company,
            'print' => true,
        ]);
    }
}