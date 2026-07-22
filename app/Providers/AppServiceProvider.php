<?php

namespace App\Providers;

use App\Models\PartUsed;
use App\Models\Payment;
use App\Observers\PartsUsedObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\JobCard;
use App\Observers\JobCardObserver;
use App\Observers\PaymentObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        JobCard::observe(JobCardObserver::class);
        Payment::observe(PaymentObserver::class);
        PartUsed::observe(PartsUsedObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
