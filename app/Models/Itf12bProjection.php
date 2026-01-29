<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itf12bProjection extends Model
{
    use HasFactory;

    protected $table = 'itf12b_projections';

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function incomeTaxYear()
    {
        return $this->belongsTo(IncomeTaxYear::class, 'income_tax_year_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }
}
