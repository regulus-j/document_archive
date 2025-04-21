<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Plan::insert([
            [
                'plan_name' => 'Basic Plan',
                'description' => 'A basic plan suitable for small businesses.',
                'price' => 9.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'feature_1' => true,
                'feature_2' => false,
                'feature_3' => false,
            ],
            [
                'plan_name' => 'Standard Plan',
                'description' => 'A standard plan for growing businesses.',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'feature_1' => true,
                'feature_2' => true,
                'feature_3' => false,
            ],
            [
                'plan_name' => 'Premium Plan',
                'description' => 'A premium plan with all features included.',
                'price' => 59.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'feature_1' => true,
                'feature_2' => true,
                'feature_3' => true,
            ],
        ]);
    }
}
