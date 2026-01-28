<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'posting_date' => 'date',
    ];

    public function vatReturn()
    {
        return $this->belongsTo(VatReturn::class);
    }
}
