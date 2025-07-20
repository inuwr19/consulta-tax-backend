<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Import model & policy
use App\Models\Appointment;
use App\Policies\AppointmentPolicy;
use App\Models\Payment;
use App\Policies\PaymentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Appointment::class => AppointmentPolicy::class,
        Payment::class     => PaymentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
