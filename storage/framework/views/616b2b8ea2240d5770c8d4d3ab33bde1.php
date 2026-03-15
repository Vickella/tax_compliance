<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>VAT7 - <?php echo e($return->return_no); ?></title>
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
        <div class="company-name"><?php echo e($company->name); ?></div>
        <div class="form-title">VAT 7 RETURN</div>
        <div>For the period: <?php echo e($return->period_start->format('d/m/Y')); ?> to <?php echo e($return->period_end->format('d/m/Y')); ?></div>
        <div>Return No: <?php echo e($return->return_no); ?></div>
    </div>

    <div class="section">
        <div class="section-title">PART A - TAXPAYER DETAILS</div>
        <table>
            <tr>
                <td width="30%">BP Number:</td>
                <td><?php echo e($company->bp_number ?? 'N/A'); ?></td>
                <td width="30%">VAT Number:</td>
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
                <?php $__currentLoopData = $return->metadata['details']['output'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item['name']); ?> (<?php echo e($item['code']); ?>)</td>
                    <td class="text-right"><?php echo e(number_format($item['amount'], 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item['vat'], 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="totals">
                    <td class="text-right">TOTAL OUTPUT TAX</td>
                    <td class="text-right"><?php echo e(number_format($return->output_vat * (100/$return->vat_rate), 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($return->output_vat, 2)); ?></td>
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
                <?php $__currentLoopData = $return->metadata['details']['input'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item['name']); ?> (<?php echo e($item['code']); ?>)</td>
                    <td class="text-right"><?php echo e(number_format($item['amount'], 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item['vat'], 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="totals">
                    <td class="text-right">TOTAL INPUT TAX</td>
                    <td class="text-right"><?php echo e(number_format($return->input_vat * (100/$return->vat_rate), 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($return->input_vat, 2)); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PART D - CALCULATION OF VAT PAYABLE/REFUNDABLE</div>
        <table>
            <tr>
                <td width="50%">Total Output Tax (from Part B)</td>
                <td class="text-right"><?php echo e(number_format($return->output_vat, 2)); ?></td>
            </tr>
            <tr>
                <td>Total Input Tax (from Part C)</td>
                <td class="text-right"><?php echo e(number_format($return->input_vat, 2)); ?></td>
            </tr>
            <tr class="totals">
                <td>VAT <?php echo e($return->vat_payable > 0 ? 'PAYABLE' : 'REFUNDABLE'); ?></td>
                <td class="text-right"><?php echo e(number_format(abs($return->vat_payable), 2)); ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Generated on: <?php echo e($generated_at->format('d/m/Y H:i:s')); ?></p>
        <p>This is a computer generated document. No signature required.</p>
    </div>
</body>
</html><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/print/vat7.blade.php ENDPATH**/ ?>