@extends('layouts.pdf')

@section('content')
<div class="pdf-header">
    <h1>Automated Tax Filing System</h1>
    <h2>Quarterly Payment Due (QPD)</h2>
    <p>Period: {{ $payment->period }}</p>
</div>

<div class="pdf-content">
    <table class="pdf-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Estimated Net Profit</td>
                <td>{{ number_format($payment->estimated_profit, 2) }}</td>
            </tr>
            <tr>
                <td>Tax Rate</td>
                <td>{{ number_format($payment->tax_rate * 100, 2) }}%</td>
            </tr>
            <tr>
                <td>Payment Amount</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="pdf-footer">
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        <p>Status: {{ $payment->status }}</p>
    </div>
</div>
@endsection