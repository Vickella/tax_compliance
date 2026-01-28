<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayrollComponent extends Model
{
    use HasFactory;

    protected $table = 'employee_payroll_components';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollComponent()
    {
        return $this->belongsTo(PayrollComponent::class);
    }
}
