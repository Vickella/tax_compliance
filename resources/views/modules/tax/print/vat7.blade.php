<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>VAT7 - {{ $return->return_no }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .form-title { font-size: 16px; font-weight: bold; margin-top: 10px; }
        .section { margin: 20px 0; border: 1px solid #ccc; padding: 15px; }
        .section-title { font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company->name }}</div>
        <div class="form-title">VAT 7 RETURN</div>
        <div>For the period: {{ $return->period_start->format('d/m/Y') }} to {{ $return->period_end->format('d/m/Y') }}</div>
        <div>Return No: {{ $return->return_no }}</div>
    </div>

    <div class="section">
        <div class="section-title">PART A - TAXPAYER DETAILS</div>
        <table>
            <tr>
                <td width="30%">BP Number:</td>
                <td>{{ $company->bp_number ?? 'N/A' }}</td>
                <td width="30%">VAT Number:</td>
                <td>{{ $company->vat_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Physical Address:</td>
                <td colspan="3">{{ $company->physical_address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $company->email }}</td>
                <td>Phone:</td>
                <td>{{ $company->phone ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART B - DECLARATION OF OUTPUT TAX</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Value of Supply ($)</th>
                    <th class="text-right">Output Tax ($)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($return->metadata['details']['output'] ?? [] as $item)
                <tr>
                    <td>{{ $item['name'] }} ({{ $item['code'] }})</td>
                    <td class="text-right">{{ number_format($item['amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($item['vat'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="totals">
                    <td class="text-right">TOTAL OUTPUT TAX</td>
                    <td class="text-right">{{ number_format($return->output_vat * (100/$return->vat_rate), 2) }}</td>
                    <td class="text-right">{{ number_format($return->output_vat, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART C - DECLARATION OF INPUT TAX</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Cost excluding VAT ($)</th>
                    <th class="text-right">Input Tax ($)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($return->metadata['details']['input'] ?? [] as $item)
                <tr>
                    <td>{{ $item['name'] }} ({{ $item['code'] }})</td>
                    <td class="text-right">{{ number_format($item['amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($item['vat'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="totals">
                    <td class="text-right">TOTAL INPUT TAX</td>
                    <td class="text-right">{{ number_format($return->input_vat * (100/$return->vat_rate), 2) }}</td>
                    <td class="text-right">{{ number_format($return->input_vat, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART D - CALCULATION OF VAT PAYABLE/REFUNDABLE</div>
        <table>
            <tr>
                <td width="50%">Total Output Tax (from Part B)</td>
                <td class="text-right">{{ number_format($return->output_vat, 2) }}</td>
            </tr>
            <tr>
                <td>Total Input Tax (from Part C)</td>
                <td class="text-right">{{ number_format($return->input_vat, 2) }}</td>
            </tr>
            <tr class="totals">
                <td>VAT {{ $return->vat_payable > 0 ? 'PAYABLE' : 'REFUNDABLE' }}</td>
                <td class="text-right">{{ number_format(abs($return->vat_payable), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Generated on: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>This is a computer generated document. No signature required.</p>
    </div>
</body>
</html>