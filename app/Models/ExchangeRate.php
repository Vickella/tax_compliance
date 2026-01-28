<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rate' => 'decimal:8',
        'rate_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class, 'base_currency', 'code');
    }

    public function quoteCurrency()
    {
        return $this->belongsTo(Currency::class, 'quote_currency', 'code');
    }
}
