<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollComponent extends Model
{
    use HasFactory;

    protected $table = 'payroll_components';

    protected $guarded = [];

    protected $casts = [
        'taxable' => 'boolean',
        'affects_nssa' => 'boolean',
        'affects_paye' => 'boolean',
        'is_loan_component' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
