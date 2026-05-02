<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForecastProfile extends Model
{
    use HasFactory;

    protected $table = 'forecast_profiles';
    
    protected $fillable = [
        'company_id',
        'account_id',
        'forecast_method',
        'fixed_amount',
        'growth_rate',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'fixed_amount' => 'decimal:2',
        'growth_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}