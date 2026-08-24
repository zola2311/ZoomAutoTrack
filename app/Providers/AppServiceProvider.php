<?php

namespace App\Providers;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Observers\InvoiceObserver;
use App\Policies\CustomerPolicy;
use App\Policies\VehiclePolicy;
use App\Models\PartUsed;
use App\Models\Payment;
use App\Observers\PartsUsedObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\JobCard;
use App\Observers\JobCardObserver;
use App\Observers\PaymentObserver;
use App\Policies\JobCardPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Invoice;
use App\Models\InventoryItem;
use App\Models\Supplier;
use App\Policies\InvoicePolicy;
use App\Policies\InventoryItemPolicy;
use App\Policies\SupplierPolicy;
use App\Models\InspectionItem;
use App\Policies\UserPolicy;
use App\Policies\InspectionItemPolicy;
use App\Models\Branch;
use App\Policies\BranchPolicy;
use App\Observers\AppointmentObserver;
use App\Models\Appointment;
use App\Policies\AppointmentPolicy;
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
        Invoice::observe(InvoiceObserver::class);
        Appointment::observe(AppointmentObserver::class);
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(JobCard::class, JobCardPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Vehicle::class, VehiclePolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(InventoryItem::class, InventoryItemPolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(InspectionItem::class, InspectionItemPolicy::class);
        Gate::policy(Branch::class, BranchPolicy::class);
    }
}
