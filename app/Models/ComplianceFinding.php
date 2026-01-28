<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceFinding extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
