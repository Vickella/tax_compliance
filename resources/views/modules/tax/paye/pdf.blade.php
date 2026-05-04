@extends('layouts.pdf')

@section('content')
<div class="pdf-header">
    <h1>Automated Tax Filing System</h1>
    <h2>PAYE Return</h2>
    <p>Period: {{ $return->period }}</p>
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
                <td>Gross Pay</td>
                <td>{{ number_format($return->gross_pay, 2) }}</td>
            </tr>
            <tr>
                <td>PAYE Deducted</td>
                <td>{{ number_format($return->paye_deducted, 2) }}</td>
            </tr>
            <tr>
                <td>Employees Count</td>
                <td>{{ $return->employees_count }}</td>
            </tr>
        </tbody>
    </table>

    <div class="pdf-footer">
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        <p>Status: {{ $return->status }}</p>
    </div>
</div>
@endsection