<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayeBracket extends Model
{
    use HasFactory;

    protected $table = 'paye_brackets';

    protected $guarded = [];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
