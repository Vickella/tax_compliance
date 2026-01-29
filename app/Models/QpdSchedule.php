<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QpdSchedule extends Model
{
    use HasFactory;

    protected $table = 'qpd_schedules';

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function incomeTaxYear()
    {
        return $this->belongsTo(IncomeTaxYear::class, 'income_tax_year_id');
    }

    public function installments()
    {
        return $this->hasMany(QpdInstallment::class, 'qpd_schedule_id');
    }
}
