<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QpdInstallment extends Model
{
    use HasFactory;

    protected $table = 'qpd_installments';

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(QpdSchedule::class, 'qpd_schedule_id');
    }
}
