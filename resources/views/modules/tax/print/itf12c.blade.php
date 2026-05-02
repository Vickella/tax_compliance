<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ITF12C - {{ $return->return_no }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .form-title { font-size: 16px; font-weight: bold; margin-top: 10px; }
        .section { margin: 20px 0; border: 1px solid #ccc; padding: 15px; }
        .section-title { font-weight: bold; margin-bottom: 10px; background: #f0f0f0; padding: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { font-weight: bold; background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
        .declaration { margin-top: 30px; }
        .signature-line { border-top: 1px solid #000; width: 300px; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company->name }}</div>
        <div class="form-title">INCOME TAX SELF ASSESSMENT RETURN (ITF12C)</div>
        <div>Tax Year Ended 31 December {{ $return->tax_year }}</div>
        <div>Return No: {{ $return->return_no }}</div>
    </div>

    <div class="section">
        <div class="section-title">PART A - TAXPAYER PARTICULARS</div>
        <table>
            <tr>
                <td width="30%">Business Partner No:</td>
                <td>{{ $company->bp_number ?? 'N/A' }}</td>
                <td width="30%">Nature of Business:</td>
                <td>{{ $company->business_nature ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Telephone:</td>
                <td>{{ $company->phone ?? 'N/A' }}</td>
                <td>Email:</td>
                <td>{{ $company->email }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART B - DETAILS PER ACCOUNTS</div>
        <table>
            <tr>
                <td width="60%">1. Turnover (Total Income)</td>
                <td class="text-right">{{ number_format($return->total_income, 2) }}</td>
            </tr>
            <tr>
                <td>2. Cost of Sales</td>
                <td class="text-right">{{ number_format($return->metadata['expense_breakdown_summary']['cogs'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>3. Gross Profit (1-2)</td>
                <td class="text-right">{{ number_format($return->total_income - ($return->metadata['expense_breakdown_summary']['cogs'] ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td>4. Other Income</td>
                <td class="text-right">{{ number_format($return->metadata['other_income'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>5. Total Income (3+4)</td>
                <td class="text-right">{{ number_format($return->total_income, 2) }}</td>
            </tr>
            <tr>
                <td>6. Total Expenses</td>
                <td class="text-right">{{ number_format($return->total_expenses, 2) }}</td>
            </tr>
            <tr class="totals">
                <td>7. Net Profit Before Tax (5-6)</td>
                <td class="text-right">{{ number_format($return->taxable_income - $return->add_back_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART C - TAX COMPUTATION</div>
        <table>
            <tr>
                <td width="60%">8. Net Profit Before Tax (from 7 above)</td>
                <td class="text-right">{{ number_format($return->taxable_income - $return->add_back_amount, 2) }}</td>
            </tr>
            <tr>
                <td>9. Add Back: Non-Deductible Expenditure</td>
                <td class="text-right">{{ number_format($return->add_back_amount, 2) }}</td>
            </tr>
            <tr class="totals">
                <td>10. Taxable Income (8+9)</td>
                <td class="text-right">{{ number_format($return->taxable_income, 2) }}</td>
            </tr>
            @if($return->assessed_loss_bf > 0)
            <tr>
                <td>11. Less: Assessed Loss Brought Forward</td>
                <td class="text-right">({{ number_format($return->assessed_loss_bf, 2) }})</td>
            </tr>
            <tr class="totals">
                <td>12. Taxable Income After Loss</td>
                <td class="text-right">{{ number_format($return->taxable_income_after_loss, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>13. Tax at {{ $return->tax_rate }}%</td>
                <td class="text-right">{{ number_format($return->income_tax, 2) }}</td>
            </tr>
            <tr>
                <td>14. Add: AIDS Levy (3% of Income Tax)</td>
                <td class="text-right">{{ number_format($return->aids_levy, 2) }}</td>
            </tr>
            <tr class="totals">
                <td>15. TOTAL TAX CHARGEABLE (13+14)</td>
                <td class="text-right">{{ number_format($return->total_tax, 2) }}</td>
            </tr>
            <tr>
                <td>16. Less: Provisional Tax Paid (QPD)</td>
                <td class="text-right">({{ number_format($return->qpd_paid, 2) }})</td>
            </tr>
            <tr class="totals">
                <td>17. BALANCE DUE / (REFUNDABLE)</td>
                <td class="text-right">{{ number_format($return->balance_due, 2) }}</td>
            </tr>
            @if($return->assessed_loss_cf > 0)
            <tr>
                <td>18. Assessed Loss Carried Forward</td>
                <td class="text-right">{{ number_format($return->assessed_loss_cf, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART D - SCHEDULE OF NON-DEDUCTIBLE EXPENDITURE</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount ($)</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($return->metadata['addback_breakdown'] ?? [] as $item)
                <tr>
                    <td>{{ $item['name'] }} ({{ $item['code'] }})</td>
                    <td class="text-right">{{ number_format($item['amount'], 2) }}</td>
                    <td>{{ $item['reason'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">No non-deductible items</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="declaration">
        <div class="section-title">DECLARATION</div>
        <p>I, __________________________________, being the duly appointed Public Officer/Representative of the taxpayer, hereby declare that this return contains a complete, just and true statement of the required particulars in regard to the income tax of the taxpayer for the accounting year ending 31 December {{ $return->tax_year }}.</p>
        
        <div style="margin-top: 40px;">
            <table style="border: none;">
                <tr>
                    <td style="border: none;">Signature: ______________________________</td>
                    <td style="border: none;">Date: {{ $generated_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="border: none;">Full Name: ______________________________</td>
                    <td style="border: none;">Designation: ______________________________</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Generated on: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>This is a computer generated document based on the submitted return.</p>
    </div>
</body>
</html>