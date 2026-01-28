<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }

    public function payrollComponent()
    {
        return $this->belongsTo(PayrollComponent::class);
    }
}
