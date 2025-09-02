<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Project;
use App\Models\Facility;
use App\Models\Equipment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(20)->create();

        Program::factory(15)->create();

        // Facility::factory(20)->create();

        // Project::factory(50)->create();

        Facility::factory(20)->create()->each(function ($facility) {
            Project::factory(3)->create([
                'facility_ID' => $facility->facility_ID,
                'program_ID' => Program::inRandomOrder()->first()->program_ID,
            ]);
        });

        Equipment::factory(30)->create();
    }
    
}
