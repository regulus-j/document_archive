<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Feature;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First create the plans
        $basicPlan = Plan::firstOrCreate(
            ['plan_name' => 'Basic Plan'],
            [
                'description' => 'A basic plan suitable for small businesses.',
                'price' => 9.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ]
        );

        $standardPlan = Plan::firstOrCreate(
            ['plan_name' => 'Standard Plan'],
            [
                'description' => 'A standard plan for growing businesses.',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ]
        );

        $premiumPlan = Plan::firstOrCreate(
            ['plan_name' => 'Premium Plan'],
            [
                'description' => 'A premium plan with all features included.',
                'price' => 59.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ]
        );

        // Get all features
        $documentStorage = Feature::where('key', 'document-storage')->first();
        $advancedSharing = Feature::where('key', 'advanced-sharing')->first();
        $analytics = Feature::where('key', 'analytics')->first();

        // Assign features to plans with proper data type handling
        if ($documentStorage) {
            $basicPlan->features()->syncWithoutDetaching([$documentStorage->id => ['enabled' => true]]);
            $standardPlan->features()->syncWithoutDetaching([$documentStorage->id => ['enabled' => true]]);
            $premiumPlan->features()->syncWithoutDetaching([$documentStorage->id => ['enabled' => true]]);
        }

        if ($advancedSharing) {
            $basicPlan->features()->syncWithoutDetaching([$advancedSharing->id => ['enabled' => false]]);
            $standardPlan->features()->syncWithoutDetaching([$advancedSharing->id => ['enabled' => true]]);
            $premiumPlan->features()->syncWithoutDetaching([$advancedSharing->id => ['enabled' => true]]);
        }

        if ($analytics) {
            $basicPlan->features()->syncWithoutDetaching([$analytics->id => ['enabled' => false]]);
            $standardPlan->features()->syncWithoutDetaching([$analytics->id => ['enabled' => false]]);
            $premiumPlan->features()->syncWithoutDetaching([$analytics->id => ['enabled' => true]]);
        }
    }
}
