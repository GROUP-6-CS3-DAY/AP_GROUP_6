<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Ensure this is UUID
            $table->string('program_id');
            $table->string('facility_id');
            $table->string('title');
            $table->string('nature_of_project');
            $table->text('description');
            $table->string('innovation_focus');
            $table->string('prototype_stage');
            $table->text('testing_requirements');
            $table->text('commercialization_plan');
            $table->string('status')->default('planning');
            $table->json('technical_requirements')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
