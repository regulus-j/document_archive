<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentCategory;

class DocumentCategories extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            1 => 'Letter',
            2 => 'Memo',
            3 => 'Reports',
            4 => 'Proposal',
            5 => 'Presentation',
            6 => 'Others',
        ];

        foreach ($categories as $id => $category) {
            // Check if category already exists
            $existingCategory = DocumentCategory::where('id', $id)
                ->orWhere('category', $category)
                ->first();
            
            if (!$existingCategory) {
                DocumentCategory::create([
                    'id' => $id,
                    'category' => $category,
                ]);
            } else {
                // Update if category name changed but ID is the same
                if ($existingCategory->category !== $category && $existingCategory->id === $id) {
                    $existingCategory->category = $category;
                    $existingCategory->save();
                }
            }
        }
    }
}
