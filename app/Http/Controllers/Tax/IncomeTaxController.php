<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\IncomeTaxService;
use App\Models\Tax\IncomeTaxReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $submittedCount = IncomeTaxReturn::where('company_id', company_id())
            ->whereIn('status', ['SUBMITTED', 'APPROVED'])
            ->count();

        $draftCount = IncomeTaxReturn::where('company_id', company_id())
            ->where('status', 'DRAFT')
            ->count();

        $totalTax = IncomeTaxReturn::where('company_id', company_id())
            ->sum('total_tax');

        return view('modules.tax.income.index', compact('returns', 'submittedCount', 'draftCount', 'totalTax'));
    }

    /**
     * Show form to create new income tax return
     */
    public function create(Request $request)
{
    $taxYear = $request->get('tax_year', now()->year);

    try {
        // STEP 1: Log start
        \Log::debug('CREATE: Starting', ['tax_year' => $taxYear]);
        
        // STEP 2: Check company_id
        $companyId = company_id();
        \Log::debug('CREATE: Company ID', ['company_id' => $companyId]);
        
        // STEP 3: Initialize service
        $this->service = new IncomeTaxService($companyId);
        \Log::debug('CREATE: Service initialized');
        
        // STEP 4: Calculate
        $calculation = $this->service->calculate($taxYear);
        \Log::debug('CREATE: Calculation completed', ['keys' => array_keys($calculation)]);
        
        // STEP 5: Check view
        if (!view()->exists('modules.tax.income.create')) {
            throw new \Exception('View not found: modules.tax.income.create');
        }
        \Log::debug('CREATE: View exists');
        
        // STEP 6: Return view
        \Log::debug('CREATE: Rendering view');
        return view('modules.tax.income.create', compact('calculation', 'taxYear'));
        
    } catch (\Throwable $e) {
        // Log the FULL error
        \Log::error('CREATE: ERROR', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // SHOW the error on screen
        return response("<pre>ERROR: " . $e->getMessage() . "\n\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() . "\n\n" . $e->getTraceAsString() . "</pre>", 500);
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
            'assessed_loss_bf' => 'nullable|numeric',
            'assessed_loss_cf' => 'nullable|numeric',
            'qpd_paid' => 'nullable|numeric',
            'balance_due' => 'nullable|numeric',
            'tax_rate' => 'required|numeric',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,submit',
        ]);

        try {
            // Recalculate to ensure accuracy
            $calculation = $this->service->calculate($validated['tax_year']);
            
            $data = array_merge($validated, [
                'income_breakdown' => $calculation['income']['breakdown'] ?? [],
                'expense_breakdown' => $calculation['expenses']['breakdown'] ?? [],
                'addback_breakdown' => $calculation['expenses']['addback_breakdown'] ?? [],
                'tax_rate' => $calculation['tax_rate'],
                'assessed_loss_bf' => $request->input('assessed_loss_bf', $calculation['assessed_loss_bf'] ?? 0),
                'assessed_loss_cf' => $calculation['assessed_loss_cf'] ?? 0,
                'qpd_paid' => $request->input('qpd_paid', $calculation['qpd_paid'] ?? 0),
                'balance_due' => $calculation['total_tax'] - $request->input('qpd_paid', $calculation['qpd_paid'] ?? 0),
                'filing_date' => now()->toDateString(),
            ]);

            $return = $this->service->saveReturn($data, auth()->id());

            if ($request->action === 'submit') {
                $return->update([
                    'status' => 'SUBMITTED',
                    'submitted_by' => auth()->id(),
                    'submitted_at' => now(),
                ]);
                $message = 'Income tax return submitted successfully';
            } else {
                $message = 'Income tax return saved as draft';
            }

            // FIXED: Added 'modules.' prefix to route name
            return redirect()->route('modules.tax.income.show', $return)
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to save return', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
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

        return redirect()->route('modules.tax.income.show', $return)
            ->with('success', 'Income tax return submitted successfully');
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

        $path = $this->service->exportReturnToCsv($return);
        
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