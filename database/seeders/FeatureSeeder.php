<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'name' => 'Document Storage',
                'key' => 'document-storage',
                'description' => 'Store and manage documents in the system',
            ],
            [
                'name' => 'Advanced Sharing',
                'key' => 'advanced-sharing',
                'description' => 'Share documents with advanced permission controls',
            ],
            [
                'name' => 'Analytics',
                'key' => 'analytics',
                'description' => 'View detailed analytics on document usage',
            ],
        ];

        foreach ($features as $feature) {
            Feature::firstOrCreate(['key' => $feature['key']], $feature);
        }
    }
}