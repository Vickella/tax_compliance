<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'posting_date' => 'date',
        'due_date' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }

    public function lines()
    {
        return $this->hasMany(PurchaseInvoiceLine::class);
    }
}
