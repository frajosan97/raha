<?php

namespace App\Providers;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Get subscription_plans categories and subscription_plans, then pass them to the view
        View::share([
            'subscription_plans' => SubscriptionPlan::all(),
            'escorts' => User::paginate(12)
        ]);
    }
}
