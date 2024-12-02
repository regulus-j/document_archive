<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Unclassified',
            'Confidential',
            'Secret',
            'Top Secret'
        ];

        foreach ($categories as $category) {
            DB::table('document_categories')->insert([
                'category' => $category,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
