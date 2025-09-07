<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Project;
use App\Models\Facility;
use App\Models\Equipment;
use App\Models\Outcome;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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

        Project::all()->each(function ($project) {
            Outcome::factory(rand(1, 3))->create([
                'project_ID' => $project->project_ID,
            ]);
        });
    }
}
