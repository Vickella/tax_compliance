<?php

namespace App\Providers;

use App\Services\PayrollCalculator;
use App\Services\TaxCalculator;
use Illuminate\Support\ServiceProvider;

class TaxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PayrollCalculator::class, function () {
            return new PayrollCalculator();
        });

        $this->app->singleton(TaxCalculator::class, function () {
            return new TaxCalculator();
        });
    }
}
