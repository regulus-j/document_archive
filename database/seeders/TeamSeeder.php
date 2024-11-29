<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Team::create(['name' => 'Team Alpha']);
        Team::create(['name' => 'Team Bravo']);
        Team::create(['name' => 'Team Charlie']);
        Team::create(['name' => 'Team Delta']);
        Team::create(['name' => 'Team Echo']);
    }
}
