<?php

namespace App\Services\Sales;

use App\Models\{SalesInvoice, StockLedgerEntry, Item};
use App\Services\Accounting\{CoaResolver, JournalPostingService};
use Illuminate\Support\Facades\DB;

class SalesPostingService
{
    public function __construct(
        private CoaResolver $coa,
        private JournalPostingService $posting
    ) {}

    public function submit(SalesInvoice $invoice, int $userId): void
    {
        if ($invoice->status !== 'DRAFT') {
            throw new \RuntimeException('Only DRAFT invoices can be submitted.');
        }

        $invoice->load(['lines', 'lines.item']);

        DB::transaction(function () use ($invoice, $userId) {

            $companyId = (int)$invoice->company_id;

            // Required accounts
            $ar      = $this->coa->require($companyId, '1200');
            $sales   = $this->coa->require($companyId, '4000-SALES');
            $vatOut  = $this->coa->require($companyId, '2200');
            $cogsAcc = $this->coa->require($companyId, '5000-COGS');
            $invAcc  = $this->coa->require($companyId, '1300');

            $postingDate = $invoice->posting_date->format('Y-m-d');
            $currency = $invoice->currency;
            $rate = (float)($invoice->exchange_rate ?? 1);

            // Build ALL journal lines first
            $lines = [];

            // DR AR total
            $lines[] = [
                'account_id' => $ar->id,
                'description' => 'AR - ' . $invoice->invoice_no,
                'debit' => (float)$invoice->total,
                'credit' => 0.0,
                'party_type' => 'CUSTOMER',
                'party_id' => (int)$invoice->customer_id,
            ];

            // CR Sales subtotal
            $lines[] = [
                'account_id' => $sales->id,
                'description' => 'Sales - ' . $invoice->invoice_no,
                'debit' => 0.0,
                'credit' => (float)$invoice->subtotal,
                'party_type' => 'NONE',
            ];

            // CR VAT Output
            if ((float)$invoice->vat_amount > 0) {
                $lines[] = [
                    'account_id' => $vatOut->id,
                    'description' => 'VAT Output - ' . $invoice->invoice_no,
                    'debit' => 0.0,
                    'credit' => (float)$invoice->vat_amount,
                    'party_type' => 'NONE',
                ];
            }

            // Stock + COGS/Inventory lines
            foreach ($invoice->lines as $line) {
                $item = $line->item ?? Item::query()
                    ->where('company_id', $companyId)
                    ->where('id', $line->item_id)
                    ->first();

                if (!$item) continue;

                if (($item->item_type ?? null) === 'STOCK' && $line->warehouse_id) {

                    $costPrice = (float)($item->cost_price ?? 0);
                    $qty = (float)$line->qty;
                    $cost = $costPrice > 0 ? ($qty * $costPrice) : 0;

                    // Stock ledger entry (keep this)
                    StockLedgerEntry::create([
                        'company_id' => $companyId,
                        'item_id' => (int)$line->item_id,
                        'warehouse_id' => (int)$line->warehouse_id,
                        'posting_date' => $postingDate,
                        'posting_time' => now()->format('H:i:s'),
                        'voucher_type' => 'SalesInvoice',
                        'voucher_id' => (int)$invoice->id,
                        'qty' => bcmul((string)$qty, '-1', 4),
                        'unit_cost' => $costPrice ?: null,
                        'value_change' => $cost > 0 ? ($cost * -1) : null,
                    ]);

                    // GL entries for COGS/Inventory
                    if ($cost > 0) {
                        $lines[] = [
                            'account_id' => $cogsAcc->id,
                            'description' => 'COGS - ' . $invoice->invoice_no,
                            'debit' => $cost,
                            'credit' => 0.0,
                            'party_type' => 'NONE',
                        ];

                        $lines[] = [
                            'account_id' => $invAcc->id,
                            'description' => 'Inventory - ' . $invoice->invoice_no,
                            'debit' => 0.0,
                            'credit' => $cost,
                            'party_type' => 'NONE',
                        ];
                    }
                }
            }

            // ✅ CORRECT METHOD - This creates AND posts the journal (GL entries!)
            $je = $this->posting->createPostedJournalWithLines(
                $companyId,
                $postingDate,
                'Sales Invoice ' . $invoice->invoice_no,
                'SalesInvoice',
                (int)$invoice->id,
                $currency,
                $rate,
                $userId,
                $lines  // Pass all lines at once
            );

            // Mark invoice submitted
            $invoice->status = 'SUBMITTED';
            $invoice->submitted_by = $userId;
            $invoice->submitted_at = now();
            $invoice->journal_entry_id = $je->id; // Save reference
            $invoice->save();
        });
    }
}