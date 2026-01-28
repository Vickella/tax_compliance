<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'base_currency', 'code');
    }
}
