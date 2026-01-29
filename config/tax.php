<?php

return [
    'vat_rate' => 0.155,
    'income_tax_rate' => 0.2575,
    'aids_levy_rate' => 0.03,
    'nssa_rate' => 0.045,
    'nec_rate' => 0.015,
    'paye_brackets' => [
        ['min' => 0.00, 'max' => 100.00, 'rate' => 0.00, 'deduct' => 0.00],
        ['min' => 100.01, 'max' => 300.00, 'rate' => 0.20, 'deduct' => 20.00],
        ['min' => 300.01, 'max' => 1000.00, 'rate' => 0.25, 'deduct' => 35.00],
        ['min' => 1000.01, 'max' => 2000.00, 'rate' => 0.30, 'deduct' => 85.00],
        ['min' => 2000.01, 'max' => 3000.00, 'rate' => 0.35, 'deduct' => 185.00],
        ['min' => 3000.01, 'max' => null, 'rate' => 0.40, 'deduct' => 335.00],
    ],
    'qpd_rates' => [0.10, 0.25, 0.30, 0.35],
];
