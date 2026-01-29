<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function period()
    {
        return $this->belongsTo(FiscalPeriod::class, 'period_id');
    }

    public function lines()
    {
        return $this->hasMany(VatReturnLine::class);
    }
}
