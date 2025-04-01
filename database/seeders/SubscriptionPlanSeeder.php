<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptionPlans = [
            [
                'name' => 'Basic',
                'duration_days' => 7,
                'price' => 200.00,
                'listing_priority' => 1,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Premium',
                'duration_days' => 30,
                'price' => 600.00,
                'listing_priority' => 2,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Pro',
                'duration_days' => 180,
                'price' => 2400.00,
                'listing_priority' => 3,
                'is_featured' => true,
                'is_active' => true
            ]
        ];

        foreach ($subscriptionPlans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
