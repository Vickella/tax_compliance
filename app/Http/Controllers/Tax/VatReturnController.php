<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\VatReturnService;
use App\Models\Tax\VatReturn;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VatReturnController extends Controller
{
    protected VatReturnService $service;

    public function __construct()
    {
        $this->service = new VatReturnService(company_id());
    }

    /**
     * Display list of VAT returns
     */
    public function index(Request $request): View
    {
        $returns = VatReturn::where('company_id', company_id())
            ->orderBy('period_start', 'desc')
            ->paginate(20);

        return view('modules.tax.vat.index', compact('returns'));
    }

    /**
     * Show form to create new VAT return
     */
    public function create(Request $request): View|RedirectResponse
{
    $periodStart = $request->get('period_start', now()->startOfMonth()->toDateString());
    $periodEnd = $request->get('period_end', now()->endOfMonth()->toDateString());

    try {
        $calculation = $this->service->calculate($periodStart, $periodEnd);
        
        // Debug log to see what's in the calculation
        Log::info('VAT Calculation result', ['calculation' => $calculation]);
        
        return view('modules.tax.vat.create', compact('calculation', 'periodStart', 'periodEnd'));
    } catch (\Exception $e) {
        Log::error('VAT calculation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Calculation failed: ' . $e->getMessage());
    }
}

    /**
     * Store VAT return - FIXED
     */
    public function store(Request $request): RedirectResponse
{
    // Log the start
    Log::info('=== VAT STORE STARTED ===', ['time' => now()->toDateTimeString()]);
    
    try {
        // Log all input
        Log::info('Request data:', $request->all());
        
        // Validate
        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'output_vat' => 'required|numeric',
            'input_vat' => 'required|numeric',
            'vat_payable' => 'required|numeric',
            'vat_rate' => 'required|numeric',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,submit',
        ]);
        
        Log::info('Validation passed', $validated);
        
        // Check for existing return
        $existingReturn = VatReturn::where('company_id', company_id())
            ->where('period_start', $validated['period_start'])
            ->where('period_end', $validated['period_end'])
            ->first();
            
        if ($existingReturn) {
            Log::warning('Duplicate return detected');
            return back()->with('error', 'A VAT return already exists for this period.')
                ->withInput();
        }
        
        // Recalculate
        Log::info('Calling service->calculate()');
        $calculation = $this->service->calculate($validated['period_start'], $validated['period_end']);
        Log::info('Calculation result:', $calculation);
        
        // Prepare data
        $data = [
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'vat_rate' => $validated['vat_rate'],
            'output_vat' => $calculation['output_vat'],
            'input_vat' => $calculation['input_vat'],
            'net_vat_payable' => $calculation['net_vat_payable'],
            'taxable_sales' => $calculation['taxable_sales'] ?? 0,
            'taxable_purchases' => $calculation['taxable_purchases'] ?? 0,
            'output_tax' => $calculation['output_vat'],
            'input_tax' => $calculation['input_vat'],
            'net_vat' => $calculation['net_vat_payable'],
            'notes' => $validated['notes'] ?? null,
            'details' => $calculation['details'],
        ];
        
        Log::info('Saving with data:', $data);
        
        // Save
        $vatReturn = $this->service->saveReturn($data, auth()->id());
        
        Log::info('Save successful', ['return_id' => $vatReturn->id]);
        
        // Handle action
        if ($request->action === 'submit') {
            $vatReturn->update([
                'status' => 'SUBMITTED',
                'submitted_by' => auth()->id(),
                'submitted_at' => now(),
            ]);
            $message = 'VAT return submitted successfully';
            Log::info('Return submitted');
        } else {
            $message = 'VAT return saved as draft';
        }
        
        Log::info('Redirecting to show page');
        return redirect()->route('modules.tax.vat.show', $vatReturn)
            ->with('success', $message);
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed', ['errors' => $e->errors()]);
        throw $e;
    } catch (\Exception $e) {
        Log::error('Store failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
    }
}
    /**
     * Show VAT return details
     */
    public function show(VatReturn $vatReturn): View
    {
        abort_unless($vatReturn->company_id === company_id(), 403);

        return view('modules.tax.vat.show', [
            'return' => $vatReturn
        ]);
    }

    /**
     * Submit VAT return
     */
    public function submit(VatReturn $vatReturn): RedirectResponse
    {
        abort_unless($vatReturn->company_id === company_id(), 403);

        if ($vatReturn->status !== 'DRAFT') {
            return back()->with('error', 'Only draft returns can be submitted');
        }

        $vatReturn->update([
            'status' => 'SUBMITTED',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        Log::info('VAT return submitted via submit method', ['return_id' => $vatReturn->id]);

        // FIXED: Use correct route name
        return redirect()->route('modules.tax.vat.show', $vatReturn)
            ->with('success', 'VAT return submitted successfully');
    }

    /**
     * Download PDF
     */
    public function downloadPdf(VatReturn $vatReturn)
    {
        abort_unless($vatReturn->company_id === company_id(), 403);

        return $this->service->generateVat7Pdf($vatReturn);
    }

    /**
     * Download CSV/Excel
     */
    public function downloadCsv(VatReturn $vatReturn): BinaryFileResponse
    {
        abort_unless($vatReturn->company_id === company_id(), 403);

        $path = $this->service->exportReturnToCsv($vatReturn);
        
        return response()->download(storage_path("app/public/{$path}"))
            ->deleteFileAfterSend(true);
    }

    /**
     * Print view
     */
    public function print(VatReturn $vatReturn): View
    {
        abort_unless($vatReturn->company_id === company_id(), 403);

        return view('modules.tax.print.vat7', [
            'return' => $vatReturn,
            'company' => $vatReturn->company,
            'print' => true,
        ]);
    }
}