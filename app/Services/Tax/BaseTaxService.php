<?php

namespace App\Services\Tax;

use App\Models\Tax\TaxSetting;
use App\Models\Tax\TaxMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class BaseTaxService
{
    protected $companyId;
    protected $taxSettings;
    protected $startDate;
    protected $endDate;

    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
        $this->taxSettings = TaxSetting::where('company_id', $companyId)->first();
        
        if (!$this->taxSettings) {
            throw new \Exception("Tax settings not configured for this company.");
        }
    }

    /**
     * Get account balance from GL entries
     */
    protected function getAccountBalance(string $accountCode, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = DB::table('gl_entries as gl')
            ->join('chart_of_accounts as coa', 'gl.account_id', '=', 'coa.id')
            ->where('gl.company_id', $this->companyId)
            ->where('coa.code', $accountCode);

        if ($startDate) {
            $query->where('gl.posting_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('gl.posting_date', '<=', $endDate);
        }

        $debit = (float) $query->sum('gl.debit');
        $credit = (float) $query->sum('gl.credit');

        return $debit - $credit;
    }

    /**
     * Get account balance for a range
     */
    protected function getAccountRangeBalance(array $accountCodes, ?string $startDate = null, ?string $endDate = null): float
    {
        if (empty($accountCodes)) {
            return 0;
        }

        $query = DB::table('gl_entries as gl')
            ->join('chart_of_accounts as coa', 'gl.account_id', '=', 'coa.id')
            ->where('gl.company_id', $this->companyId)
            ->whereIn('coa.code', $accountCodes);

        if ($startDate) {
            $query->where('gl.posting_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('gl.posting_date', '<=', $endDate);
        }

        $debit = (float) $query->sum('gl.debit');
        $credit = (float) $query->sum('gl.credit');

        return $debit - $credit;
    }

    /**
     * Get account balances with breakdown
     */
    protected function getAccountBalances(array $accountCodes, ?string $startDate = null, ?string $endDate = null): array
    {
        if (empty($accountCodes)) {
            return [];
        }

        $results = DB::table('gl_entries as gl')
            ->join('chart_of_accounts as coa', 'gl.account_id', '=', 'coa.id')
            ->where('gl.company_id', $this->companyId)
            ->whereIn('coa.code', $accountCodes)
            ->when($startDate, fn($q) => $q->where('gl.posting_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('gl.posting_date', '<=', $endDate))
            ->groupBy('coa.code', 'coa.name')
            ->select(
                'coa.code',
                'coa.name',
                DB::raw('SUM(gl.debit) as total_debit'),
                DB::raw('SUM(gl.credit) as total_credit'),
                DB::raw('SUM(gl.debit - gl.credit) as net_balance')
            )
            ->orderBy('coa.code')
            ->get();

        $balances = [];
        foreach ($results as $row) {
            $balances[$row->code] = [
                'name' => $row->name,
                'debit' => (float) $row->total_debit,
                'credit' => (float) $row->total_credit,
                'net' => (float) $row->net_balance,
            ];
        }

        return $balances;
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv(array $data, string $filename): string
    {
        $path = "exports/tax/{$filename}";
        $fullPath = storage_path("app/public/{$path}");
        
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = fopen($fullPath, 'w');
        
        // Add headers
        if (!empty($data['headers'])) {
            fputcsv($file, $data['headers']);
        }

        // Add rows
        if (!empty($data['rows'])) {
            foreach ($data['rows'] as $row) {
                fputcsv($file, $row);
            }
        }

        // Add summary
        if (!empty($data['summary'])) {
            fputcsv($file, []); // Empty line
            fputcsv($file, ['SUMMARY']);
            foreach ($data['summary'] as $key => $value) {
                fputcsv($file, [$key, $value]);
            }
        }

        fclose($file);

        return $path;
    }

    /**
     * Format currency
     */
    protected function formatCurrency($amount): string
    {
        return number_format($amount, 2, '.', ',');
    }

    /**
     * Format date
     */
    protected function formatDate($date): string
    {
        if (!$date) {
            return '';
        }
        return date('d/m/Y', strtotime($date));
    }
}