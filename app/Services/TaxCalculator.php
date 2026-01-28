<?php

namespace App\Services;

class TaxCalculator
{
    public function calculateVat(float $amount): float
    {
        return $this->roundMoney($amount * config('tax.vat_rate'));
    }

    public function calculateIncomeTax(float $taxableIncome, float $nonDeductibleExpenses = 0.0): array
    {
        $adjustedIncome = $this->roundMoney($taxableIncome + $nonDeductibleExpenses);
        $tax = $this->roundMoney($adjustedIncome * config('tax.income_tax_rate'));

        return [
            'adjusted_income' => $adjustedIncome,
            'income_tax' => $tax,
        ];
    }

    public function calculateQpdSchedule(float $estimatedAnnualTax, array $paid = []): array
    {
        $rates = config('tax.qpd_rates');
        $schedule = [];
        $cumulative = 0.0;

        foreach ($rates as $index => $rate) {
            $cumulative += $rate;
            $amountDue = $this->roundMoney($estimatedAnnualTax * $rate);
            $amountPaid = $this->roundMoney($paid[$index] ?? 0.0);
            $schedule[] = [
                'installment_no' => $index + 1,
                'rate_this_quarter' => $rate,
                'cumulative_rate' => $cumulative,
                'amount_due' => $amountDue,
                'amount_paid' => $amountPaid,
                'variance' => $this->roundMoney($amountDue - $amountPaid),
            ];
        }

        return $schedule;
    }

    private function roundMoney(float $value): float
    {
        return round($value, 2);
    }
}
