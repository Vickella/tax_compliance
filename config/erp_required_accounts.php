<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ERP Required Accounts
    |--------------------------------------------------------------------------
    | These accounts MUST exist in chart_of_accounts for the company, and
    | MUST be active, otherwise posting (submit/approve) should be blocked.
    */

    'required_codes' => [

        /*
        |--------------------------------------------------------------------------
        | ASSETS (1000-1999)
        |--------------------------------------------------------------------------
        */
        // Current Assets
        '1000' => 'Current Assets (Control)',
        '1100' => 'Cash and Bank',
        '1110' => 'Cash In Hand',
        '1120' => 'Bank Account',
        '1150' => 'Tax Receivable - VAT',
        '1160' => 'Tax Receivable - Income Tax',
        '1170' => 'Deferred Tax Asset',
        '1200' => 'Accounts Receivable',
        '1300' => 'Inventory',
        '2210' => 'VAT Receivable',

        /*
        |--------------------------------------------------------------------------
        | LIABILITIES (2000-2999)
        |--------------------------------------------------------------------------
        */
        // Current Liabilities
        '2000' => 'Current Liabilities (Control)',
        '2100' => 'Accounts Payable',
        '2100-VAT-OUT' => 'VAT Output',
        '2100-NETPAY' => 'Net Salaries Payable',
        '2110' => 'PAYE Payable (ZIMRA)',
        '2120' => 'AIDS Levy Payable',
        '2130' => 'NSSA Payable',
        '2140' => 'NEC Payable',
        '2200' => 'VAT Payable',
        '2340-INCOME-TAX-PAY' => 'Income Tax Payable',
        '2341' => 'Income Tax Payable - Current',
        '2342' => 'Income Tax Payable - Prior Years',
        '2350-PROV-TAX-PAY' => 'Provisional Tax Payable',
        '2350' => 'Deferred Tax Liability',
        '2390' => 'Dividends Payable',

        /*
        |--------------------------------------------------------------------------
        | EQUITY (3000-3999)
        |--------------------------------------------------------------------------
        */
        '3000' => 'Equity (Control)',
        '3100' => 'Share Capital',
        '3200' => 'Retained Earnings',

        /*
        |--------------------------------------------------------------------------
        | INCOME (4000-4999)
        |--------------------------------------------------------------------------
        */
        '4000' => 'Income (Control)',
        '4000-SALES' => 'Sales Revenue',        
     

        /*
        |--------------------------------------------------------------------------
        | COST OF SALES (5000-5999)
        |--------------------------------------------------------------------------
        */
        '5000' => 'Cost of Sales (Control)',
        '5000-COGS' => 'Cost of Goods Sold',
        '5000-PAYROLL' => 'Payroll Expense',
        '5001-PAYROLL-STAT' => 'Payroll Statutory Expense (Employer)',

        /*
        |--------------------------------------------------------------------------
        | EXPENSES (6000-6999)
        |--------------------------------------------------------------------------
        */
        '6000' => 'Operating Expenses (Control)',
        '6100' => 'Salaries and Wages',
        '6200' => 'Rent Expense',
        '6300' => 'Utilities Expense',
        '6310' => 'Depreciation Expense',
        '6320' => 'Fines and Penalties',
        '6330' => 'Donations',
        '6340' => 'Marketing and Advertising',
        '6350' => 'Entertainment Expenses',
        '6360' => 'Interest Expense',
        '6370' => 'Disallowable Legal Expenses',
        '6380' => 'Capital Expenditure Written Off',
        '6400' => 'Wear and Tear Allowance',
        '6410' => 'Scrapping Allowance',
        '6900' => 'Bank Charges',
    ],

    /*
    |--------------------------------------------------------------------------
    | Control Accounts (Subledger Required)
    |--------------------------------------------------------------------------
    */
    'control_accounts' => [
        '1200' => 'Accounts Receivable',
        '2100' => 'Accounts Payable',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Account Mappings
    |--------------------------------------------------------------------------
    */
    'tax_mappings' => [
        'vat_output' => ['2100-VAT-OUT', '2200'],
        'vat_input' => ['2210-VAT-IN', '2210', '1150'],
        'income_tax_payable' => ['2340-INCOME-TAX-PAY', '2341', '2342'],
        'provisional_tax_payable' => ['2350-PROV-TAX-PAY'],
        'paye_payable' => ['2110'],
        'aids_levy_payable' => ['2120'],
        'nssa_payable' => ['2130'],
        'nec_payable' => ['2140'],
        'non_deductible' => ['6320', '6330', '6350', '6370', '6380'],
        'partly_deductible' => [
            '6350' => 50, // Entertainment - 50% deductible
        ],
        'capital_allowances' => ['6400', '6410'],
        'depreciation' => ['6310'],
        'payroll_expense' => ['5000-PAYROLL', '6100'],
        'payroll_statutory_expense' => ['5001-PAYROLL-STAT'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Rate Defaults
    |--------------------------------------------------------------------------
    */
    'tax_rate_defaults' => [
        'vat_standard' => 15.5,
        'corporate_income_tax' => 25.75,
        'aids_levy' => 3,
        'nssa_employer' => 4.5,
        'qpd_quarter1' => 10,
        'qpd_quarter2' => 25,
        'qpd_quarter3' => 30,
        'qpd_quarter4' => 35,
    ],

    /*
    |--------------------------------------------------------------------------
    | Penalty Defaults
    |--------------------------------------------------------------------------
    */
    'penalty_defaults' => [
        'late_submission' => 1000,
        'late_payment_monthly' => 5,
        'max_penalty_months' => 12,
    ],
];