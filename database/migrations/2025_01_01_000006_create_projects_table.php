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
            $table->id('project_id');
            $table->foreignId('program_id')->constrained('programs', 'program_id')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities', 'facility_id')->onDelete('cascade');
            $table->string('title');
            $table->enum('nature_of_project', ['research', 'prototype', 'applied_work']);
            $table->text('description');
            $table->string('innovation_focus'); // IoT devices, smart home, renewable energy
            $table->enum('prototype_stage', ['concept', 'prototype', 'mvp', 'market_launch']);
            $table->text('testing_requirements'); // Compliance and performance checks
            $table->text('commercialization_plan'); // Path to market adoption
            $table->timestamps();
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
