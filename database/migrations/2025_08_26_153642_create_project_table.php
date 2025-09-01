<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_ID');
            $table->foreignID('program_ID')->constrained('program')->onDelete('cascade');
            $table->foreignID('facility_ID')->constrained('facility')->onDelete('cascade');
            $table->string('title');
            $table->string('nature_of_project');
            $table->text('description');
            $table->string('innovation_focus');
            $table->string('prototype_stage');
            $table->string('testing_requirements');
            $table->string('commercialization_plan');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
