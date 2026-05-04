@extends('layouts.pdf')

@section('content')
<div class="pdf-header">
    <h1>Automated Tax Filing System</h1>
    <h2>Income Tax Return</h2>
    <p>Tax Year: {{ $return->tax_year }}</p>
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
                <td>Gross Income</td>
                <td>{{ number_format($return->gross_income, 2) }}</td>
            </tr>
            <tr>
                <td>Deductions</td>
                <td>{{ number_format($return->deductions, 2) }}</td>
            </tr>
            <tr>
                <td>Taxable Income</td>
                <td>{{ number_format($return->taxable_income, 2) }}</td>
            </tr>
            <tr>
                <td>Tax Due</td>
                <td>{{ number_format($return->tax_due, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="pdf-footer">
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        <p>Status: {{ $return->status }}</p>
    </div>
</div>
@endsection