<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    protected $table = 'payment_allocations';
    
    protected $fillable = [
        'payment_id',
        'reference_type',
        'reference_id',
        'allocated_amount',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Remove the where clause - it's trying to query the wrong table
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'reference_id');
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'reference_id');
    }
}