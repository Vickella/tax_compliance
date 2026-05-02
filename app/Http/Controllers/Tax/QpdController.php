<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\QpdService;
use App\Models\Tax\QpdPayment;
use App\Models\Tax\Itf12bProjection;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QpdController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new QpdService(company_id());
    }

    /**
     * Display QPD forecast and payments
     */
    public function index(Request $request)
{
    $taxYear = $request->get('tax_year', now()->year);
    
    try {
        // Create service instance with the tax year
        $service = new QpdService(company_id(), $taxYear);
        
        // Generate forecast data to get QPD estimates
        $forecastedTB = $service->generateForecastedTB();
        $forecastedPL = $service->generateForecastedPL($forecastedTB);
        $taxComputation = $service->generateTaxComputation($forecastedPL, $forecastedTB);
        $qpdEstimates = $service->generateQPDEstimates($taxComputation);
        
        // Get actual payments
        $payments = QpdPayment::where('company_id', company_id())
            ->where('tax_year', $taxYear)
            ->orderBy('quarter')
            ->get();
        
        // ✅ FORCE REFRESH: Get actual paid amounts from database for each quarter
        foreach ($qpdEstimates as $q => $estimate) {
            $actualPaid = QpdPayment::where('company_id', company_id())
                ->where('tax_year', $taxYear)
                ->where('quarter', $q)
                ->where('status', 'PAID')
                ->sum('amount');
                
            $qpdEstimates[$q]['paid_amount'] = $actualPaid;
            $qpdEstimates[$q]['balance_due'] = max(0, $estimate['amount'] - $actualPaid);
        }
        
        // Calculate summary values
        $estimatedAnnualTax = $taxComputation['total_tax'] ?? 0;
        $totalQpd = collect($qpdEstimates)->sum('amount');
        $calculatedAt = $taxComputation['calculated_at'] ?? now();

        return view('modules.tax.qpd.index', compact(
            'qpdEstimates', 
            'payments', 
            'taxYear',
            'estimatedAnnualTax',
            'totalQpd',
            'calculatedAt'
        ));
        
    } catch (\Exception $e) {
        Log::error('QPD index error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Failed to load QPD data: ' . $e->getMessage());
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
            // Create service with tax year
            $service = new QpdService(company_id(), $taxYear);
            
            // Generate proper forecast data
            $forecastedTB = $service->generateForecastedTB();
            $forecastedPL = $service->generateForecastedPL($forecastedTB);
            $taxComputation = $service->generateTaxComputation($forecastedPL, $forecastedTB);
            $qpdEstimates = $service->generateQPDEstimates($taxComputation);
            
            // Get the specific quarter's estimate
            $quarterEstimate = $qpdEstimates[$quarter] ?? [
                'amount' => 0,
                'due_date' => $this->getDueDate($quarter, $taxYear),
                'percentage' => 0,
                'paid_amount' => 0,
                'balance_due' => 0
            ];
            
            // Build calculation array for the form
            $calculation = [
                'estimated_annual_tax' => $taxComputation['total_tax'] ?? 0,
                'qpd_percentage' => $quarterEstimate['percentage'] ?? 0,
                'qpd_amount' => $quarterEstimate['amount'] ?? 0,
                'paid_amount' => $this->getPaidAmount($taxYear, $quarter),
                'balance_due' => $quarterEstimate['amount'] ?? 0,
                'due_date' => $quarterEstimate['due_date'] ?? $this->getDueDate($quarter, $taxYear),
                'calculated_at' => $taxComputation['calculated_at'] ?? now(),
            ];

            return view('modules.tax.qpd.create', compact('calculation', 'taxYear', 'quarter'));
            
        } catch (\Exception $e) {
            Log::error('QPD create error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Calculation failed: ' . $e->getMessage());
        }
    }

    
public function store(Request $request)

{

    Log::info('QPD store - Request received', [
        'all_data' => $request->all(),
        'method' => $request->method(),
        'url' => $request->url()
    ]);
    
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

    Log::info('QPD store - Validation passed', ['validated' => $validated]);

    try {
        // Get tax settings directly from the database
        $taxSettings = \App\Models\Tax\TaxSetting::where('company_id', company_id())->first();
        
        // Get the percentage for this quarter
        $percentage = $taxSettings ? $taxSettings->getQpdPercentage($validated['quarter']) : 0;
        
        // Calculate estimated annual tax based on the payment amount and quarter
        $quarterPercentages = [1 => 10, 2 => 25, 3 => 30, 4 => 35];
        $quarterPercent = $quarterPercentages[$validated['quarter']] ?? 10;
        $estimatedAnnualTax = $validated['amount'] * (100 / $quarterPercent);
        
        $data = array_merge($validated, [
            'estimated_annual_tax' => round($estimatedAnnualTax, 2),
            'percentage_applied' => $percentage ?: $quarterPercent,
            'payment_date' => now()->toDateString(),
        ]);

        Log::info('QPD store - Data prepared', ['data' => $data]);

        $payment = $this->service->savePayment($data, auth()->id());

        Log::info('QPD store - Payment saved', [
            'payment_id' => $payment->id,
            'payment_no' => $payment->payment_no
        ]);

        if ($request->action === 'submit') {
            $payment->update(['status' => 'SUBMITTED']);
            $message = 'QPD payment submitted successfully';
        } else {
            $message = 'QPD payment saved as draft';
        }

        return redirect()->route('modules.tax.qpd.show', $payment)
            ->with('success', $message);

    } catch (\Exception $e) {
        Log::error('QPD store error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
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
     * Mark payment as paid
     */
    public function markAsPaid(QpdPayment $payment)
    {
        abort_unless($payment->company_id === company_id(), 403);

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

    // ============================================
    // NEW FORECAST METHODS
    // ============================================

    /**
     * Display forecast dashboard
     */
    public function forecastDashboard(Request $request)
    {
        $year = $request->get('year', now()->year);
        $service = new QpdService(company_id(), $year);
        
        $forecastedTB = $service->generateForecastedTB();
        $forecastedPL = $service->generateForecastedPL($forecastedTB);
        $taxComputation = $service->generateTaxComputation($forecastedPL, $forecastedTB);
        $qpdEstimates = $service->generateQPDEstimates($taxComputation);
        
        return view('modules.tax.qpd.forecast_dashboard', compact(
            'forecastedTB', 'forecastedPL', 'taxComputation', 'qpdEstimates', 'year'
        ));
    }

    /**
     * View forecasted trial balance
     */
    public function forecastTB(Request $request)
    {
        $year = $request->get('year', now()->year);
        $service = new QpdService(company_id(), $year);
        $forecastedTB = $service->generateForecastedTB();
        
        return view('modules.tax.qpd.forecast_tb', compact('forecastedTB', 'year'));
    }

    /**
     * View forecasted P&L
     */
    public function forecastPL(Request $request)
    {
        $year = $request->get('year', now()->year);
        $service = new QpdService(company_id(), $year);
        
        $forecastedTB = $service->generateForecastedTB();
        $forecastedPL = $service->generateForecastedPL($forecastedTB);
        
        return view('modules.tax.qpd.forecast_pl', compact('forecastedPL', 'year'));
    }

    /**
     * View forecasted tax computation
     */
    public function forecastTax(Request $request)
    {
        $year = $request->get('year', now()->year);
        $service = new QpdService(company_id(), $year);
        
        $forecastedTB = $service->generateForecastedTB();
        $forecastedPL = $service->generateForecastedPL($forecastedTB);
        $taxComputation = $service->generateTaxComputation($forecastedPL, $forecastedTB);
        
        return view('modules.tax.qpd.forecast_tax', compact('taxComputation', 'year'));
    }

    /**
     * View QPD estimates from forecast
     */
    public function forecastQPD(Request $request)
    {
        $year = $request->get('year', now()->year);
        $service = new QpdService(company_id(), $year);
        
        $forecastedTB = $service->generateForecastedTB();
        $forecastedPL = $service->generateForecastedPL($forecastedTB);
        $taxComputation = $service->generateTaxComputation($forecastedPL, $forecastedTB);
        $qpdEstimates = $service->generateQPDEstimates($taxComputation);
        
        return view('modules.tax.qpd.forecast_qpd', compact('qpdEstimates', 'year'));
    }

    /**
     * Manage forecast profiles (using Itf12bProjection)
     */
    public function forecastProfiles(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $profiles = Itf12bProjection::where('company_id', company_id())
            ->where('tax_year', $year)
            ->get();
        
        $accounts = ChartOfAccount::where('company_id', company_id())
            ->where('is_active', 1)
            ->orderBy('code')
            ->get();
        
        return view('modules.tax.qpd.forecast_profiles', compact('profiles', 'accounts', 'year'));
    }

    /**
     * Store forecast profile
     */
    public function storeForecastProfile(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:chart_of_accounts,id',
            'tax_year' => 'required|integer',
            'forecast_method' => 'required|in:linear,fixed,average',
            'fixed_amount' => 'nullable|numeric|min:0',
            'growth_rate' => 'nullable|numeric|min:-100|max:100',
            'notes' => 'nullable|string',
        ]);

        Itf12bProjection::updateOrCreate(
            [
                'company_id' => company_id(),
                'account_id' => $validated['account_id'],
                'tax_year' => $validated['tax_year'],
            ],
            [
                'forecast_method' => $validated['forecast_method'],
                'fixed_amount' => $validated['fixed_amount'] ?? null,
                'growth_rate' => $validated['growth_rate'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Forecast profile saved');
    }

    /**
     * Helper: Get due date for a quarter
     */
    protected function getDueDate(int $quarter, int $year): string
    {
        $dates = [
            1 => $year . '-03-25',
            2 => $year . '-06-25',
            3 => $year . '-09-25',
            4 => $year . '-12-20',
        ];
        return $dates[$quarter] ?? $year . '-12-31';
    }

    /**
     * Helper: Get paid amount for a quarter
     */
    protected function getPaidAmount(int $taxYear, int $quarter): float
    {
        try {
            return (float) QpdPayment::where('company_id', company_id())
                ->where('tax_year', $taxYear)
                ->where('quarter', $quarter)
                ->where('status', 'PAID')
                ->sum('amount');
        } catch (\Exception $e) {
            return 0;
        }
    }
}