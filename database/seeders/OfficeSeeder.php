<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Office;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Office::create(['name' => 'Head Office']);
        Office::create(['name' => 'Regional Office', 'parent_office_id' => 1]);
        Office::create(['name' => 'Local Office', 'parent_office_id' => 2]);
        Office::create(['name' => 'Branch Office 1', 'parent_office_id' => 1]);
        Office::create(['name' => 'Branch Office 2', 'parent_office_id' => 1]);
    }
}
