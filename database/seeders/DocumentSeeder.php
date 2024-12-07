<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class documentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 documents
        Document::factory()->count(50)->create()->each(function ($document) {
            // Attach a random category
            $category = DocumentCategory::inRandomOrder()->first();
            if ($category) {
                $document->categories()->attach($category->id);
            }
        });
    }
}
