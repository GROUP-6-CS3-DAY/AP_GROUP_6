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
            $table->id(); // Change to standard auto-incrementing ID
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('facility_id');
            $table->string('title');
            $table->text('nature_of_project');
            $table->text('description');
            $table->string('innovation_focus');
            $table->string('prototype_stage');
            $table->text('testing_requirements');
            $table->text('commercialization_plan');
            $table->string('status')->default('planning'); // Add missing status column
            $table->json('participants')->nullable(); // Add participants array
            $table->json('outcomes')->nullable(); // Add outcomes array
            $table->json('technical_requirements')->nullable(); // Add technical requirements array
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            
            // Add indexes for better performance
            $table->index(['program_id']);
            $table->index(['facility_id']);
            $table->index(['status']);
            $table->index(['innovation_focus']);
            $table->index(['prototype_stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};