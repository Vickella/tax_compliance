<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Models\Tax\VatReturn;
use App\Models\Tax\QpdPayment;
use App\Models\Tax\IncomeTaxReturn;
use Illuminate\Http\Request;

class TaxModuleController extends Controller
{
    /**
     * Display the tax module dashboard
     */
    public function index()
    {
        $companyId = company_id();

        // Get recent activity
        $recentActivity = $this->getRecentActivity($companyId);

        // Get compliance status
        $compliance = $this->getComplianceStatus($companyId);

        // Get statistics
        $stats = $this->getStats($companyId);

        return view('modules.tax.index', compact('recentActivity', 'compliance', 'stats'));
    }

    /**
     * Get recent tax activity
     */
    protected function getRecentActivity($companyId)
    {
        $activity = [];

        // Recent VAT returns
        $vatReturns = VatReturn::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($vatReturns as $return) {
            $activity[] = [
                'description' => 'VAT Return - ' . $return->period_start->format('M Y'),
                'date' => $return->created_at->format('d M Y'),
                'status' => $return->status,
                'link' => route('modules.tax.vat.show', $return),
                'type' => 'vat'
            ];
        }

        // Recent QPD payments
        $qpdPayments = QpdPayment::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($qpdPayments as $payment) {
            $activity[] = [
                'description' => 'QPD Payment - Q' . $payment->quarter . ' ' . $payment->tax_year,
                'date' => $payment->created_at->format('d M Y'),
                'status' => $payment->status,
                'link' => route('modules.tax.qpd.show', $payment),
                'type' => 'qpd'
            ];
        }

        // Recent Income Tax returns
        $incomeReturns = IncomeTaxReturn::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($incomeReturns as $return) {
            $activity[] = [
                'description' => 'Income Tax - Year ' . $return->tax_year,
                'date' => $return->created_at->format('d M Y'),
                'status' => $return->status,
                'link' => route('modules.tax.income.show', $return),
                'type' => 'income'
            ];
        }

        // Sort by date (most recent first) and limit to 5
        usort($activity, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return array_slice($activity, 0, 5);
    }

    /**
     * Get compliance status
     */
    protected function getComplianceStatus($companyId)
    {
        $currentYear = now()->year;

        // VAT compliance
        $lastVat = VatReturn::where('company_id', $companyId)
            ->orderBy('period_start', 'desc')
            ->first();

        $vatCompliant = true;
        if ($lastVat) {
            $lastPeriodEnd = $lastVat->period_end;
            $expectedLastPeriod = now()->subMonth()->endOfMonth();
            $vatCompliant = $lastPeriodEnd >= $expectedLastPeriod;
        }

        // QPD compliance
        $qpdQuarters = [];
        $qpdCompliant = true;

        for ($q = 1; $q <= 4; $q++) {
            $payment = QpdPayment::where('company_id', $companyId)
                ->where('tax_year', $currentYear)
                ->where('quarter', $q)
                ->where('status', 'PAID')
                ->first();

            $dueDate = $this->getQpdDueDate($currentYear, $q);
            $isOverdue = !$payment && now()->gt($dueDate);

            if ($isOverdue) {
                $qpdCompliant = false;
            }

            $qpdQuarters[$q] = [
                'paid' => (bool) $payment,
                'overdue' => $isOverdue,
                'due_date' => $dueDate->format('Y-m-d')
            ];
        }

        // Income Tax compliance
        $incomeReturn = IncomeTaxReturn::where('company_id', $companyId)
            ->where('tax_year', $currentYear)
            ->first();

        $incomeDeadline = now()->setDate($currentYear + 1, 4, 30);
        $incomeCompliant = $incomeReturn || now()->lt($incomeDeadline);

        return [
            'vat' => [
                'compliant' => $vatCompliant,
                'last_filed' => $lastVat ? $lastVat->period_start->format('M Y') : 'Never',
                'next_due' => now()->addMonth()->format('Y-m-25')
            ],
            'qpd' => [
                'compliant' => $qpdCompliant,
                'quarters' => $qpdQuarters
            ],
            'income' => [
                'compliant' => $incomeCompliant,
                'status' => $incomeReturn ? $incomeReturn->status : 'Not Filed',
                'deadline' => $incomeDeadline->format('d M Y')
            ]
        ];
    }

    /**
     * Get QPD due date
     */
    protected function getQpdDueDate($year, $quarter)
    {
        $dates = [
            1 => now()->setDate($year, 3, 25),
            2 => now()->setDate($year, 6, 25),
            3 => now()->setDate($year, 9, 25),
            4 => now()->setDate($year, 12, 20)
        ];
        return $dates[$quarter] ?? now();
    }

    /**
     * Get statistics
     */
    protected function getStats($companyId)
    {
        $currentYear = now()->year;

        return [
            'vat_returns' => VatReturn::where('company_id', $companyId)->count(),
            'vat_pending' => VatReturn::where('company_id', $companyId)->where('status', 'DRAFT')->count(),
            'qpd_payments' => QpdPayment::where('company_id', $companyId)->count(),
            'qpd_overdue' => $this->getOverdueQpdCount($companyId),
            'income_returns' => IncomeTaxReturn::where('company_id', $companyId)->count(),
            'next_deadline' => $this->getNextDeadline(),
            'deadline_type' => $this->getDeadlineType()
        ];
    }

    /**
     * Get overdue QPD count
     */
    protected function getOverdueQpdCount($companyId)
    {
        $currentYear = now()->year;
        $count = 0;

        for ($q = 1; $q <= 4; $q++) {
            $payment = QpdPayment::where('company_id', $companyId)
                ->where('tax_year', $currentYear)
                ->where('quarter', $q)
                ->where('status', 'PAID')
                ->first();

            $dueDate = $this->getQpdDueDate($currentYear, $q);
            if (!$payment && now()->gt($dueDate)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get next deadline
     */
    protected function getNextDeadline()
    {
        $now = now();
        $currentYear = $now->year;

        // Check QPD deadlines
        $qpdDates = [
            ['date' => now()->setDate($currentYear, 3, 25), 'type' => 'QPD Q1'],
            ['date' => now()->setDate($currentYear, 6, 25), 'type' => 'QPD Q2'],
            ['date' => now()->setDate($currentYear, 9, 25), 'type' => 'QPD Q3'],
            ['date' => now()->setDate($currentYear, 12, 20), 'type' => 'QPD Q4']
        ];

        // VAT deadline (25th of next month)
        $vatDate = now()->addMonth()->setDate($now->year, $now->month + 1, 25);
        $deadlines[] = ['date' => $vatDate, 'type' => 'VAT'];

        // Income Tax deadline
        $incomeDate = now()->setDate($currentYear + 1, 4, 30);
        $deadlines[] = ['date' => $incomeDate, 'type' => 'Income Tax'];

        // Find next deadline
        $nextDeadline = null;
        foreach ($deadlines as $deadline) {
            if ($deadline['date']->gt($now)) {
                if (!$nextDeadline || $deadline['date']->lt($nextDeadline['date'])) {
                    $nextDeadline = $deadline;
                }
            }
        }

        return $nextDeadline ? $nextDeadline['date']->format('d M') : 'No upcoming';
    }

    /**
     * Get deadline type
     */
    protected function getDeadlineType()
    {
        $now = now();
        $currentYear = $now->year;

        $deadlines = [
            ['date' => now()->setDate($currentYear, 3, 25), 'type' => 'QPD Q1'],
            ['date' => now()->setDate($currentYear, 6, 25), 'type' => 'QPD Q2'],
            ['date' => now()->setDate($currentYear, 9, 25), 'type' => 'QPD Q3'],
            ['date' => now()->setDate($currentYear, 12, 20), 'type' => 'QPD Q4'],
            ['date' => now()->addMonth()->setDate($now->year, $now->month + 1, 25), 'type' => 'VAT'],
            ['date' => now()->setDate($currentYear + 1, 4, 30), 'type' => 'Income Tax']
        ];

        $nextDeadline = null;
        foreach ($deadlines as $deadline) {
            if ($deadline['date']->gt($now)) {
                if (!$nextDeadline || $deadline['date']->lt($nextDeadline['date'])) {
                    $nextDeadline = $deadline;
                }
            }
        }

        return $nextDeadline ? $nextDeadline['type'] : 'None';
    }
}