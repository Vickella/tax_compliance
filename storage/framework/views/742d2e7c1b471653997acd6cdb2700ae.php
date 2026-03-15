<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ITF12B - <?php echo e($payment->payment_no); ?></title>
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
        .totals { font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
        .declaration { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name"><?php echo e($company->name); ?></div>
        <div class="form-title">PROVISIONAL TAX PAYMENT (ITF12B)</div>
        <div>For the Quarter Ended <?php echo e($payment->quarter_name ?? 'Q' . $payment->quarter . ' ' . $payment->tax_year); ?></div>
        <div>Payment No: <?php echo e($payment->payment_no); ?></div>
    </div>

    <div class="section">
        <div class="section-title">TAXPAYER DETAILS</div>
        <table>
            <tr>
                <td width="30%">Name:</td>
                <td><?php echo e($company->name); ?></td>
                <td width="30%">Trade Name:</td>
                <td><?php echo e($company->trade_name ?? $company->name); ?></td>
            </tr>
            <tr>
                <td>Business Partner No:</td>
                <td><?php echo e($company->bp_number ?? 'N/A'); ?></td>
                <td>Contract Account No:</td>
                <td><?php echo e($company->vat_number ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Physical Address:</td>
                <td colspan="3"><?php echo e($company->physical_address ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo e($company->email); ?></td>
                <td>Phone:</td>
                <td><?php echo e($company->phone ?? 'N/A'); ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">ESTIMATED ANNUAL INCOME AND TAX</div>
        <table>
            <tr>
                <td width="60%">Estimated Annual Net Profit (or Loss)</td>
                <td class="text-right"><?php echo e(number_format($payment->estimated_annual_tax / ($payment->percentage_applied / 100), 2)); ?></td>
            </tr>
            <tr>
                <td>Estimated Tax Payable for the Year</td>
                <td class="text-right"><?php echo e(number_format($payment->estimated_annual_tax, 2)); ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">QUARTERLY PAYMENT DETAILS</div>
        <table>
            <tr>
                <td width="60%">Quarter</td>
                <td class="text-right">Q<?php echo e($payment->quarter); ?></td>
            </tr>
            <tr>
                <td>Payment Date</td>
                <td class="text-right"><?php echo e($payment->payment_date->format('d/m/Y')); ?></td>
            </tr>
            <tr>
                <td>Due Date</td>
                <td class="text-right"><?php echo e($payment->due_date->format('d/m/Y')); ?></td>
            </tr>
            <tr>
                <td>Percentage Applied</td>
                <td class="text-right"><?php echo e($payment->percentage_applied); ?>%</td>
            </tr>
            <tr class="totals">
                <td>QUARTERLY PAYMENT DUE</td>
                <td class="text-right"><?php echo e(number_format($payment->amount, 2)); ?></td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td class="text-right"><?php echo e($payment->payment_method); ?></td>
            </tr>
            <tr>
                <td>Reference / Receipt No</td>
                <td class="text-right"><?php echo e($payment->reference ?? 'N/A'); ?></td>
            </tr>
        </table>
    </div>

    <?php if($payment->status === 'PAID'): ?>
    <div class="section">
        <div class="section-title">PAYMENT CONFIRMATION</div>
        <p>This payment has been confirmed and processed.</p>
        <p>Payment Status: <strong>PAID</strong></p>
        <?php if($payment->journalEntry): ?>
        <p>Journal Reference: <?php echo e($payment->journalEntry->entry_no); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="declaration">
        <p><strong>Notes:</strong></p>
        <ol>
            <li>This payment is based on estimated annual taxable income.</li>
            <li>If the estimated net profit changes significantly, please adjust subsequent quarterly payments.</li>
            <li>Failure to submit this return together with payment by the due date may result in penalties and interest.</li>
        </ol>
        
        <div style="margin-top: 40px;">
            <table style="border: none;">
                <tr>
                    <td style="border: none;">Signature: ______________________________</td>
                    <td style="border: none;">Date: <?php echo e($payment->payment_date->format('d/m/Y')); ?></td>
                </tr>
                <tr>
                    <td style="border: none;">Name: ______________________________</td>
                    <td style="border: none;">Designation: ______________________________</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Generated on: <?php echo e($generated_at->format('d/m/Y H:i:s')); ?></p>
        <p>This is a computer generated document. No signature required if submitted electronically.</p>
    </div>
</body>
</html><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/print/itf12b.blade.php ENDPATH**/ ?>