<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Models\Reminder;
use App\Policies\SubscriptionPolicy;
use App\Policies\ReminderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Subscription::class => SubscriptionPolicy::class,
        Reminder::class => ReminderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}