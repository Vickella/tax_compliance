# Zimbabwe SME ERP System Design (Laravel, MySQL, Alpine.js, Node.js)

## 1. Current Project Structure Review (As-Is)

### 1.1 Monorepo Layout
- Laravel application with standard directories: `app/`, `config/`, `database/`, `resources/`, `routes/`, `tests/`, and asset tooling (`package.json`, `vite.config.js`).
- Modular infrastructure is already present using **nwidart/laravel-modules** with a `Modules/` folder and module activation file `modules_statuses.json`.【F:composer.json†L1-L35】【F:config/modules.php†L1-L127】

### 1.2 Modules
- A `Modules/Core` module exists with its own `app/`, `routes/`, `resources/`, and `database/` structure, indicating the modular architecture is in place.【F:Modules/Core/routes/web.php†L1-L8】
- The module routing uses a standard resource controller pattern with auth/verified middleware.【F:Modules/Core/routes/web.php†L1-L8】

### 1.3 Key Framework Capabilities Already Installed
- **Spatie Permission** for RBAC (`spatie/laravel-permission`).【F:composer.json†L17-L24】
- **Audit trails** via `owen-it/laravel-auditing`.【F:composer.json†L17-L24】
- **PDF & Excel exports** via `barryvdh/laravel-dompdf` and `maatwebsite/excel`.【F:composer.json†L10-L24】
- **Modular architecture** via `nwidart/laravel-modules`.【F:composer.json†L10-L24】

### 1.4 Modular Generator Configuration
- The `modules.php` config defines a standardized module scaffolding structure with `app/`, `routes/`, `resources/`, and `database/` paths, aligning with your requested modular setup.【F:config/modules.php†L48-L188】

---

## 2. High-Level Architecture

### 2.1 Architectural Overview
**Pattern:** Modular monolith (Laravel Modules) with strict domain boundaries.

**Core components:**
- **UI Layer:** Blade + Alpine.js + Tailwind, Vite for build.
- **API Layer:** REST/JSON for internal modules and external integrations (ZIMRA, fiscal devices, banks).
- **Domain Modules:** Each module self-contained with routes, controllers, services, models, migrations, and views.
- **Persistence:** MySQL, with shared schema conventions (company, currency, period).
- **Background Jobs:** Queue for OCR processing, report generation, and integrations.

### 2.2 Proposed Module Map
1. **Core** (tenants, companies, base configs, common utilities)
2. **Accounting** (chart of accounts, journals, ledgers, periods)
3. **Sales** (invoices, credit/debit notes, receivables)
4. **Purchases** (supplier invoices, bills of entry, payables)
5. **Inventory** (items, stock movements, valuation)
6. **Cashbook** (cash & bank transactions, reconciliations)
7. **Tax** (VAT, QPD, income tax, compliance analytics)
8. **Payroll** (employees, payroll processing, statutory submissions)
9. **CRM** (customers & suppliers)
10. **Security** (RBAC, audit trails, approval workflows)
11. **Reporting** (financial statements, VAT returns, payroll reports)
12. **Integrations** (ZIMRA APIs, OCR/QR scanning, payment gateways)

**Cross-cutting services:**
- Document lifecycle + audit trail
- Double-entry accounting engine
- Multi-currency engine
- Multi-company support
- Reporting/analytics

---

## 3. Low-Level Design

### 3.1 Module Structure Template
Each module follows a consistent internal layout:
```
Modules/<ModuleName>/
  app/
    Http/Controllers/
    Models/
    Services/
    Policies/
  routes/web.php
  routes/api.php
  resources/views/
  database/migrations/
  database/seeders/
```

### 3.2 Document Lifecycle
All transactional documents use common states and actions:
- **States:** Draft → Submitted/Posted → Cancelled or Reversed
- **Actions:** Create, Update, Submit/Post, Cancel, Reverse, Delete, Import, Export, Download (PDF/Excel)
- **Audit:** Every state change is logged by user, timestamp, and reason.

### 3.3 Double-Entry Accounting Engine
- All financial modules post to **Journal Entry** and **Journal Entry Lines**.
- Each document type maps to predefined accounting rules (via posting profiles).

### 3.4 Multi-Currency Handling
- Base currency per company (e.g., ZiG). Foreign currencies captured per transaction.
- Store **exchange rates** at transaction date.
- GL postings stored in base currency; original amounts retained for reporting.

### 3.5 Multi-Company / Multi-Entity
- Every transactional and master record is scoped to `company_id`.
- Shared tables (currency, tax types) are global.

---

## 4. Data Flows (Key Processes)

### 4.1 Sales Invoice Flow (VAT Invoice + QR)
1. Create invoice (Draft).
2. Validate VAT fields: customer TIN, VAT number, invoice number, tax category.
3. Generate fiscal QR (from fiscal device / API).
4. Submit → Post to GL:
   - Dr Accounts Receivable
   - Cr Sales Revenue
   - Cr Output VAT
5. Update stock ledger (if itemized).
6. Sync to VAT schedule and ZIMRA report dataset.

### 4.2 Purchase Invoice Flow
1. Capture supplier VAT/TIN, tax invoice reference, bill of entry.
2. Validate VAT compliance.
3. Submit → Post:
   - Dr Expense/Inventory
   - Dr Input VAT
   - Cr Accounts Payable
4. Update stock ledger and cost of sales if inventory items.

### 4.3 Payroll Processing
1. Run payroll per period.
2. Calculate PAYE + AIDS levy (3% of PAYE) + NSSA + ZIMDEF.
3. Post journal:
   - Dr Salary Expense / Benefits
   - Cr PAYE Payable
   - Cr NSSA Payable
   - Cr ZIMDEF Payable
   - Cr Cash/Bank (if paid)

### 4.4 VAT Return (ZIMRA VAT 7 / 7A)
1. Aggregate output VAT from sales invoices.
2. Aggregate input VAT from purchase invoices.
3. Validate invoice references and fiscal device refs.
4. Prepare VAT return schedule with input/output listings.

### 4.5 QPD Flow
1. Compute estimated annual tax.
2. Allocate QPD installments: 10%, 25%, 30%, 35% (cumulative 10%, 35%, 65%, 100%).
3. Track paid vs required, highlight variance and due dates.

---

## 5. Database Schema (Core Tables)

### 5.1 Core / Shared
- `companies`
- `company_settings`
- `currencies`
- `exchange_rates`
- `users`
- `roles`, `permissions`, `model_has_roles`, `model_has_permissions`
- `audit_logs`

### 5.2 Accounting
- `accounts` (chart of accounts)
- `journal_entries`
- `journal_lines`
- `fiscal_periods`
- `posting_profiles`

### 5.3 Sales
- `customers`
- `sales_invoices`
- `sales_invoice_lines`
- `sales_credit_notes`
- `sales_debit_notes`
- `sales_taxes`

### 5.4 Purchases
- `suppliers`
- `purchase_invoices`
- `purchase_invoice_lines`
- `purchase_credit_notes`
- `purchase_debit_notes`
- `purchase_taxes`

### 5.5 Inventory
- `items`
- `warehouses`
- `stock_movements`
- `stock_ledger`
- `item_costs`

### 5.6 Cashbook / Banking
- `bank_accounts`
- `cash_receipts`
- `cash_payments`
- `bank_reconciliations`

### 5.7 Tax
- `vat_returns`
- `vat_return_lines`
- `qpd_schedules`
- `income_tax_computations`
- `tax_rules`

### 5.8 Payroll
- `employees`
- `payroll_runs`
- `payroll_lines`
- `statutory_deductions`
- `payslips`

### 5.9 Integrations
- `fiscal_devices`
- `ocr_scans`
- `integration_logs`

---

## 6. Zimbabwe Tax & Payroll Compliance Logic

### 6.1 VAT Compliance
- Mandatory fields: VAT number, invoice number, fiscal device ref, invoice date, tax category.
- Input VAT only claimable if valid supplier VAT invoice.
- VAT return supports VAT 7 / VAT 7A output + input schedules.

### 6.2 QPD & Income Tax
- Quarterly provisional tax calculation and schedule generation.
- Annual income tax reconciliation compares estimated vs actual.
- Track installment payments and deadlines.

### 6.3 Payroll Statutory
- PAYE tax bands per ZIMRA tables (configurable by year).
- AIDS levy = 3% of PAYE.
- NSSA contributions capped by ceiling.
- ZIMDEF applies to relevant employers.

---

## 7. Integration Points

### 7.1 ZIMRA APIs (Future)
- Stub integrations for VAT return submission, income tax, and TaRMS.
- API token management with audit logs.

### 7.2 Fiscal Devices
- Import fiscal QR data.
- Store device serial, invoice references, and verification data.

### 7.3 OCR/QR Scanning
- Use Google Lens / Vision API for auto-populating invoice fields.
- Store scan results in `ocr_scans` with confidence score and raw payload.

---

## 8. Reporting & Analytics
- Financial Statements: Trial Balance, P&L, Balance Sheet.
- Tax Reports: VAT return schedules, QPD summaries, payroll statutory.
- Operational: Stock valuation, aging reports, cashflow.

---

## 9. Security & Audit
- RBAC with module-level and action-level permissions.
- Audit trails for document lifecycle changes.
- Segregation of duties for approvals.

---

## 10. Next Steps / Implementation Roadmap
1. Scaffold modules (Sales, Purchases, Inventory, Accounting, Payroll, Tax, CRM, Cashbook).
2. Implement core schema and services (company, currency, accounting engine).
3. Build document lifecycle + audit trail.
4. Build VAT + payroll compliance calculations.
5. Build reporting dashboard and exports.
6. Build integrations with ZIMRA and fiscal devices.
