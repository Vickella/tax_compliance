<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Services\Numbers\NumberSeries;
use App\Services\Accounting\JournalPostingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $q = trim((string)$request->get('q', ''));

        $journals = JournalEntry::query()
            ->with(['lines']) // Eager load lines for efficiency
            ->when($status, fn($x) => $x->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('entry_no', 'like', "%{$q}%")
                       ->orWhere('memo', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('modules.accounting.journals.index', compact('journals', 'status', 'q'));
    }

    public function create()
    {
        $journal = new JournalEntry([
            'posting_date' => now()->toDateString(),
            'currency' => company_currency(),
            'exchange_rate' => 1,
            'status' => 'DRAFT',
        ]);

        $accounts = ChartOfAccount::query()
            ->where('company_id', company_id())
            ->where('is_active', 1)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'type']);

        return view('modules.accounting.journals.create', compact('journal', 'accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0.00000001'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'integer', 'exists:chart_of_accounts,id'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $companyId = company_id();

        return DB::transaction(function () use ($data, $companyId) {
            // Calculate totals
            $totalDebit = collect($data['lines'])->sum('debit');
            $totalCredit = collect($data['lines'])->sum('credit');

            if (round($totalDebit, 2) !== round($totalCredit, 2)) {
                return back()->withErrors(['lines' => 'Journal is not balanced. Total debit must equal total credit.'])
                    ->withInput();
            }

            // Generate entry number
            $entryNo = NumberSeries::next('JE', $companyId, 'journal_entries', 'entry_no');

            // Create journal entry
            $journal = JournalEntry::create([
                'company_id' => $companyId,
                'entry_no' => $entryNo,
                'posting_date' => $data['posting_date'],
                'memo' => $data['memo'] ?? null,
                'status' => 'DRAFT',
                'currency' => $data['currency'],
                'exchange_rate' => $data['exchange_rate'],
                'created_by' => auth()->id(),
            ]);

            // Create journal lines
            foreach ($data['lines'] as $line) {
                JournalLine::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $line['account_id'],
                    'description' => $line['description'] ?? null,
                    'debit' => (float)($line['debit'] ?? 0),
                    'credit' => (float)($line['credit'] ?? 0),
                    'party_type' => 'NONE',
                    'party_id' => null,
                ]);
            }

            return redirect()->route('modules.accounting.journals.show', $journal)
                ->with('success', 'Journal saved as Draft.');
        });
    }

    public function show(JournalEntry $journal)
    {
        abort_unless($journal->company_id === company_id(), 403);
        
        $journal->load(['lines.account']);
        
        return view('modules.accounting.journals.show', compact('journal'));
    }

    public function edit(JournalEntry $journal)
    {
        abort_unless($journal->company_id === company_id(), 403);
        abort_if($journal->status !== 'DRAFT', 403, 'Only DRAFT journals can be edited.');

        $journal->load('lines');

        $accounts = ChartOfAccount::query()
            ->where('company_id', company_id())
            ->where('is_active', 1)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'type']);

        return view('modules.accounting.journals.edit', compact('journal', 'accounts'));
    }

    public function update(Request $request, JournalEntry $journal)
    {
        abort_unless($journal->company_id === company_id(), 403);
        abort_if($journal->status !== 'DRAFT', 403, 'Only DRAFT journals can be updated.');

        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0.00000001'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'integer', 'exists:chart_of_accounts,id'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data, $journal) {
            // Calculate totals
            $totalDebit = collect($data['lines'])->sum('debit');
            $totalCredit = collect($data['lines'])->sum('credit');

            if (round($totalDebit, 2) !== round($totalCredit, 2)) {
                return back()->withErrors(['lines' => 'Journal is not balanced. Total debit must equal total credit.'])
                    ->withInput();
            }

            // Update journal header
            $journal->update([
                'posting_date' => $data['posting_date'],
                'memo' => $data['memo'] ?? null,
                'currency' => $data['currency'],
                'exchange_rate' => $data['exchange_rate'],
            ]);

            // Delete old lines
            JournalLine::where('journal_entry_id', $journal->id)->delete();

            // Create new lines
            foreach ($data['lines'] as $line) {
                JournalLine::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $line['account_id'],
                    'description' => $line['description'] ?? null,
                    'debit' => (float)($line['debit'] ?? 0),
                    'credit' => (float)($line['credit'] ?? 0),
                    'party_type' => 'NONE',
                    'party_id' => null,
                ]);
            }

            return redirect()->route('modules.accounting.journals.show', $journal)
                ->with('success', 'Journal updated.');
        });
    }

    /**
     * Post a journal entry to GL
     */
    public function post(JournalEntry $journal)
    {
        abort_unless($journal->company_id === company_id(), 403);

        // Check if journal can be posted
        if ($journal->status !== 'DRAFT') {
            return back()->with('error', 'This journal cannot be posted (current status: ' . $journal->status . ').');
        }

        // Make sure we have lines
        $lines = $journal->lines;
        if ($lines->isEmpty()) {
            return back()->with('error', 'Cannot post: journal has no lines.');
        }

        try {
            // Use JournalPostingService to create GL entries
            $postingService = app(JournalPostingService::class);
            $postingService->postJournalEntry($journal, auth()->id());

            return redirect()
                ->route('modules.accounting.journals.show', $journal)
                ->with('success', 'Journal posted successfully. GL entries created.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to post journal: ' . $e->getMessage());
        }
    }

    /**
     * Reverse a posted journal entry
     */
    public function reverse(JournalEntry $journal)
    {
        abort_unless($journal->company_id === company_id(), 403);
        
        if ($journal->status !== 'POSTED') {
            return back()->with('error', 'Only POSTED journals can be reversed.');
        }

        try {
            DB::transaction(function () use ($journal) {
                // Create reversal entry
                $reversalNo = NumberSeries::next('REV', $journal->company_id, 'journal_entries', 'entry_no');
                
                $reversal = JournalEntry::create([
                    'company_id' => $journal->company_id,
                    'entry_no' => $reversalNo,
                    'posting_date' => now()->toDateString(),
                    'memo' => 'Reversal of ' . $journal->entry_no . ': ' . $journal->memo,
                    'status' => 'DRAFT',
                    'currency' => $journal->currency,
                    'exchange_rate' => $journal->exchange_rate,
                    'created_by' => auth()->id(),
                ]);

                // Create reversed lines (swap debits and credits)
                foreach ($journal->lines as $line) {
                    JournalLine::create([
                        'journal_entry_id' => $reversal->id,
                        'account_id' => $line->account_id,
                        'description' => 'Reversal: ' . ($line->description ?? ''),
                        'debit' => $line->credit,
                        'credit' => $line->debit,
                        'party_type' => $line->party_type,
                        'party_id' => $line->party_id,
                    ]);
                }

                // Post the reversal
                $postingService = app(JournalPostingService::class);
                $postingService->postJournalEntry($reversal, auth()->id());

                // Mark original as reversed
                $journal->update([
                    'status' => 'REVERSED',
                    'reversal_id' => $reversal->id,
                ]);
            });

            return redirect()
                ->route('modules.accounting.journals.show', $journal)
                ->with('success', 'Journal reversed successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reverse journal: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a draft journal
     */
    public function cancel(JournalEntry $journal)
    {
        abort_unless($journal->company_id === company_id(), 403);
        abort_if($journal->status !== 'DRAFT', 403, 'Only DRAFT journals can be cancelled.');

        $journal->update(['status' => 'CANCELLED']);

        return redirect()
            ->route('modules.accounting.journals.index')
            ->with('success', 'Journal cancelled.');
    }
}