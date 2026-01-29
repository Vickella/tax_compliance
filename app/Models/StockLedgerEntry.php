<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLedgerEntry extends Model
{
    use HasFactory;

    protected $table = 'stock_ledger_entries';

    protected $guarded = [];

    protected $casts = [
        'posting_date' => 'date',
        'posting_time' => 'datetime:H:i:s',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
