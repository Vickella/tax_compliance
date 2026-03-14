<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    
    protected $fillable = [
        'company_id',
        'payment_no',
        'payment_type',
        'party_type',        // ADD THIS
        'party_id',          // ADD THIS
        'bank_account_id',   // CHANGE FROM payment_account_id
        'posting_date',
        'currency',
        'exchange_rate',
        'amount',
        'reference',         // CHANGE FROM reference_no
        'status',
        'created_by',
        'submitted_by',
        'submitted_at',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'submitted_at' => 'datetime',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'party_id')->where('party_type', 'CUSTOMER');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'party_id')->where('party_type', 'SUPPLIER');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}