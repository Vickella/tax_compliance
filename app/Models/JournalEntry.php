<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'posting_date' => 'date',
        'posted_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }

    public function lines()
    {
        return $this->hasMany(JournalLine::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
