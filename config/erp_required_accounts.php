<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ERP Required Accounts
    |--------------------------------------------------------------------------
    | These accounts MUST exist in chart_of_accounts for the company, and
    | MUST be active, otherwise posting (submit/approve) should be blocked.
    |
    | - Keys are COA codes (string)
    | - Values are human-friendly names (used for validation messaging)
    |
    | NOTE:
    | 1) You can change codes later to match your COA template.
    | 2) Keep codes stable once transactions exist.
    */

    'required_codes' => [

        /*
        |--------------------------------------------------------------------------
        | ASSETS
        |--------------------------------------------------------------------------
        */
        // Current Assets
        '1000' => 'Current Assets (Control)',
        '1100' => 'Cash and Bank',
        '1110' => 'Cash In Hand',
        '1120' => 'Bank - Main',
        '1150' => 'Tax Receivable - VAT',
        '1160' => 'Tax Receivable - Income Tax',
        '1170' => 'Deferred Tax Asset',
        '1200' => 'Accounts Receivable (Debtors)',
        '1210' => 'Allowance for Doubtful Debts',
        '1300' => 'Inventory (Stock In Hand)',
        '1310' => 'Goods in Transit',
        '1320' => 'Inventory Adjustments / Write-offs',

        // Non-Current Assets
        '1500' => 'Non-Current Assets (Control)',
        '1510' => 'Property Plant and Equipment',
        '1520' => 'Accumulated Depreciation',
        '1530' => 'Intangible Assets',

        /*
        |--------------------------------------------------------------------------
        | LIABILITIES
        |--------------------------------------------------------------------------
        */
        // Current Liabilities
        '2000' => 'Current Liabilities (Control)',
        '2100' => 'Accounts Payable (Creditors)',
        '2110' => 'PAYE Payable (ZIMRA)',
        '2120' => 'AIDS Levy Payable',
        '2130' => 'NSSA Payable',
        '2140' => 'NEC Payable',
        '2150' => 'Pension Payable',
        '2160' => 'Medical Aid Payable',
        '2200' => 'VAT Payable',
        '2210' => 'VAT Receivable',
        '2300' => 'PAYE Payable',
        '2310' => 'NSSA Payable',
        '2320' => 'AIDS Levy Payable',
        '2330' => 'ZIMDEF Payable',
        '2340' => 'Income Tax Payable',
        '2341' => 'Income Tax Payable - Current',
        '2342' => 'Income Tax Payable - Prior Years',
        '2350' => 'Provisional Tax Payable',
        '2351' => 'Deferred Tax Liability',
        '2390' => 'Dividends Payable',
        '2400' => 'Withholding Tax Payable',

        // Non-Current Liabilities
        '2500' => 'Non-Current Liabilities (Control)',
        '2510' => 'Loans Payable',

        /*
        |--------------------------------------------------------------------------
        | EQUITY
        |--------------------------------------------------------------------------
        */
        '3000' => 'Equity (Control)',
        '3100' => 'Share Capital',
        '3200' => 'Retained Earnings',
        '3300' => 'Current Year Earnings',

        /*
        |--------------------------------------------------------------------------
        | INCOME
        |--------------------------------------------------------------------------
        */
        '4000' => 'Income (Control)',
        '4100' => 'Sales Revenue',
        '4110' => 'Sales Returns and Allowances',
        '4200' => 'Service Revenue',
        '4300' => 'Other Income',

        /*
        |--------------------------------------------------------------------------
        | COST OF SALES / COGS
        |--------------------------------------------------------------------------
        */
        '5000' => 'Cost of Sales (Control)',
        '5100' => 'Cost of Goods Sold',
        '5200' => 'Inventory Adjustments (COGS Impact)',

        /*
        |--------------------------------------------------------------------------
        | EXPENSES (Operating)
        |--------------------------------------------------------------------------
        */
        '6000' => 'Operating Expenses (Control)',
        '6100' => 'Salaries and Wages',
        '6110' => 'NSSA Employer Expense',
        '6120' => 'ZIMDEF Employer Expense',
        '6130' => 'AIDS Levy Expense',
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
        '6400' => 'Transport and Fuel',
        '6500' => 'Repairs and Maintenance',
        '6600' => 'Office Supplies',
        '6700' => 'Internet and Communication',
        '6800' => 'Professional Fees',
        '6900' => 'Bank Charges',
        '6950' => 'Depreciation Expense',

        /*
        |--------------------------------------------------------------------------
        | CAPITAL ALLOWANCES (Tax Adjustments)
        |--------------------------------------------------------------------------
        */
        '6400' => 'Wear and Tear Allowance',
        '6410' => 'Scrapping Allowance',

        /*
        |--------------------------------------------------------------------------
        | FINANCE COSTS / TAX
        |--------------------------------------------------------------------------
        */
        '7000' => 'Finance Costs (Control)',
        '7100' => 'Interest Expense',

        '8000' => 'Tax Expense (Control)',
        '8100' => 'Income Tax Expense',
        '8110' => 'Deferred Tax Expense',
        '8200' => 'Penalties and Interest Expense',
    ],

    /*
    |--------------------------------------------------------------------------
    | Control Accounts (Subledger Required)
    |--------------------------------------------------------------------------
    | These accounts should be marked as is_control_account = 1.
    | They represent balances tied to party subledgers (customers/suppliers).
    */
    'control_accounts' => [
        '1200' => 'Accounts Receivable (Debtors)',
        '2100' => 'Accounts Payable (Creditors)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Account Mappings
    |--------------------------------------------------------------------------
    | These are used by the tax calculation services to identify which accounts
    | relate to specific tax treatments.
    */
    'tax_mappings' => [
        'vat_output' => ['2200'],
        'vat_input' => ['2210'],
        'income_tax_payable' => ['2340', '2341', '2342'],
        'provisional_tax_payable' => ['2350'],
        'non_deductible' => ['6310', '6320', '6330', '6350', '6370', '6380', '6950'],
        'partly_deductible' => [
            '6350' => 50, // Entertainment - 50% deductible
        ],
        'capital_allowances' => ['6400', '6410'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Rate Defaults
    |--------------------------------------------------------------------------
    | Default tax rates if not set in tax_settings table
    */
    'tax_rate_defaults' => [
        'vat_standard' => 15,
        'corporate_income_tax' => 25.75,
        'aids_levy' => 3,
        'qpd_quarter1' => 10,
        'qpd_quarter2' => 25,
        'qpd_quarter3' => 30,
        'qpd_quarter4' => 35,
    ],

    /*
    |--------------------------------------------------------------------------
    | Penalty Defaults
    |--------------------------------------------------------------------------
    | Default penalty amounts and rates
    */
    'penalty_defaults' => [
        'late_submission' => 1000,
        'late_payment_monthly' => 5,
        'max_penalty_months' => 12,
    ],
];