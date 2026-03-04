<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\VatReturnService;
use App\Models\Tax\VatReturn;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
            return view('modules.tax.vat.create', compact('calculation', 'periodStart', 'periodEnd'));
        } catch (\Exception $e) {
            return back()->with('error', 'Calculation failed: ' . $e->getMessage());
        }
    }

    /**
     * Store VAT return
     */
    public function store(Request $request): RedirectResponse
    {
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

        try {
            // Recalculate to ensure accuracy
            $calculation = $this->service->calculate($validated['period_start'], $validated['period_end']);
            
            // Verify matches user input
            if (abs($calculation['vat_payable'] - $validated['vat_payable']) > 0.01) {
                return back()->with('error', 'VAT calculation mismatch. Please refresh and try again.')
                    ->withInput();
            }

            $data = array_merge($validated, [
                'details' => $calculation['details'],
                'filing_date' => now()->toDateString(),
            ]);

            $vatReturn = $this->service->saveReturn($data, auth()->id());

            if ($request->action === 'submit') {
                $vatReturn->update(['status' => 'SUBMITTED']);
                $message = 'VAT return submitted successfully';
            } else {
                $message = 'VAT return saved as draft';
            }

            return redirect()->route('tax.vat.show', $vatReturn)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save VAT return: ' . $e->getMessage())
                ->withInput();
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

        return back()->with('success', 'VAT return submitted successfully');
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