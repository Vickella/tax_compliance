<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    use HasFactory;

    protected $table = 'payroll_runs';

    protected $guarded = [];

    protected $casts = [
        'processed_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function period()
    {
        return $this->belongsTo(FiscalPeriod::class, 'period_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'gl_journal_entry_id');
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }
}
