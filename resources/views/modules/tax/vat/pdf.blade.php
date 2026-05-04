@extends('layouts.pdf')

@section('content')
<div class="pdf-header">
    <h1>Automated Tax Filing System</h1>
    <h2>VAT Return</h2>
    <p>Period: {{ $return->period_start->format('M Y') }}</p>
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
                <td>VAT Collected</td>
                <td>{{ number_format($return->vat_collected, 2) }}</td>
            </tr>
            <tr>
                <td>VAT Paid</td>
                <td>{{ number_format($return->vat_paid, 2) }}</td>
            </tr>
            <tr>
                <td>Net VAT</td>
                <td>{{ number_format($return->net_vat, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="pdf-footer">
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        <p>Status: {{ $return->status }}</p>
    </div>
</div>
@endsection