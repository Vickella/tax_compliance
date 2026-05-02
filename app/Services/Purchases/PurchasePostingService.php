<?php

namespace App\Services\Purchases;

use App\Models\ChartOfAccount;
use App\Models\PurchaseInvoice;
use App\Models\Item;
use App\Services\Accounting\JournalPostingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchasePostingService
{
    public function __construct(
        private JournalPostingService $posting
    ) {}

    public function submit(PurchaseInvoice $invoice, int $userId): void
    {
        if ($invoice->status !== 'DRAFT') {
            throw new \RuntimeException('Only DRAFT invoices can be submitted.');
        }

        $invoice->load(['lines', 'lines.item']);

        DB::transaction(function () use ($invoice, $userId) {
            $companyId = (int)$invoice->company_id;

            // Get account IDs
            $ap       = $this->getAccount($companyId, '2100');      // Accounts Payable
            $inventory = $this->getAccount($companyId, '1300');    // Inventory
            $vatInput = $this->getAccount($companyId, '2210-VAT-IN'); // VAT Input
            $purchase = $this->getAccount($companyId, '5000');      // Purchases/COGS

            $postingDate = $invoice->posting_date->format('Y-m-d');
            $currency = $invoice->currency;
            $rate = (float)($invoice->exchange_rate ?? 1);

            // Build ALL journal lines
            $lines = [];

            // DR Purchases/Expense (subtotal)
            $lines[] = [
                'account_id' => $purchase->id,
                'description' => "Purchase - {$invoice->invoice_no}",
                'debit' => (float)$invoice->subtotal,
                'credit' => 0.0,
                'party_type' => 'SUPPLIER',
                'party_id' => (int)$invoice->supplier_id,
            ];

            // DR VAT Input (if applicable)
            if ((float)$invoice->vat_amount > 0) {
                $lines[] = [
                    'account_id' => $vatInput->id,
                    'description' => "VAT Input - {$invoice->invoice_no}",
                    'debit' => (float)$invoice->vat_amount,
                    'credit' => 0.0,
                    'party_type' => 'SUPPLIER',
                    'party_id' => (int)$invoice->supplier_id,
                ];
            }

            // CR Accounts Payable (total)
            $lines[] = [
                'account_id' => $ap->id,
                'description' => "AP - {$invoice->invoice_no}",
                'debit' => 0.0,
                'credit' => (float)$invoice->total,
                'party_type' => 'SUPPLIER',
                'party_id' => (int)$invoice->supplier_id,
            ];

            // Create and POST journal entry (THIS CREATES GL ENTRIES!)
            $journalEntry = $this->posting->createPostedJournalWithLines(
                $companyId,
                $postingDate,
                "Purchase Invoice {$invoice->invoice_no}",
                'PurchaseInvoice',
                (int)$invoice->id,
                $currency,
                $rate,
                $userId,
                $lines
            );

            // Update invoice status
            $invoice->status = 'SUBMITTED';
            $invoice->submitted_by = $userId;
            $invoice->submitted_at = now();
            $invoice->journal_entry_id = $journalEntry->id;
            $invoice->save();

            Log::info('Purchase invoice submitted with GL entries', [
                'invoice_no' => $invoice->invoice_no,
                'journal_id' => $journalEntry->id,
                'lines_count' => count($lines)
            ]);
        });
    }

    private function getAccount(int $companyId, string $code): ChartOfAccount
    {
        $account = ChartOfAccount::query()
            ->where('company_id', $companyId)
            ->where('code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$account) {
            throw new \RuntimeException("Account code {$code} not found for company {$companyId}");
        }

        return $account;
    }
}