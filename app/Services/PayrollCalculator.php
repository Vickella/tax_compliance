<?php

namespace App\Services;

class PayrollCalculator
{
    public function calculate(array $earnings): array
    {
        $gross = $this->sum($earnings);
        $nssa = $this->roundMoney($gross * config('tax.nssa_rate'));
        $nec = $this->roundMoney($gross * config('tax.nec_rate'));
        $taxable = $this->roundMoney($gross - $nssa);
        $paye = $this->calculatePaye($taxable);
        $aidsLevy = $this->roundMoney($paye * config('tax.aids_levy_rate'));

        return [
            'gross' => $gross,
            'nssa' => $nssa,
            'nec' => $nec,
            'taxable' => $taxable,
            'paye' => $paye,
            'aids_levy' => $aidsLevy,
            'total_deductions' => $this->roundMoney($nssa + $nec + $paye + $aidsLevy),
            'net_pay' => $this->roundMoney($gross - $nssa - $nec - $paye - $aidsLevy),
        ];
    }

    public function summarize(array $runs): array
    {
        $totals = [
            'basic_salary' => 0.0,
            'housing_allowance' => 0.0,
            'transport_allowance' => 0.0,
            'other_income' => 0.0,
            'gross' => 0.0,
            'nssa' => 0.0,
            'nec' => 0.0,
            'taxable' => 0.0,
            'paye' => 0.0,
            'aids_levy' => 0.0,
            'total_deductions' => 0.0,
            'net_pay' => 0.0,
        ];

        foreach ($runs as $run) {
            foreach ($totals as $key => $value) {
                $totals[$key] = $this->roundMoney($totals[$key] + ($run[$key] ?? 0.0));
            }
        }

        return $totals;
    }

    private function calculatePaye(float $taxable): float
    {
        $brackets = config('tax.paye_brackets');

        foreach ($brackets as $bracket) {
            $min = $bracket['min'];
            $max = $bracket['max'];
            if ($taxable >= $min && ($max === null || $taxable <= $max)) {
                return $this->roundMoney(($taxable * $bracket['rate']) - $bracket['deduct']);
            }
        }

        return 0.0;
    }

    private function sum(array $values): float
    {
        return $this->roundMoney(array_sum($values));
    }

    private function roundMoney(float $value): float
    {
        return round($value, 2);
    }
}
