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
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id('outcome_id');
            $table->foreignId('project_id')->constrained('projects', 'project_id')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('artifact_link')->nullable(); // Link to the deliverable artifact
            $table->enum('outcome_type', ['cad', 'pcb', 'prototype', 'report', 'business_plan']);
            $table->string('quality_certification')->nullable(); // Compliance or test results
            $table->enum('commercialization_status', ['demoed', 'market_linked', 'launched'])->default('demoed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcomes');
    }
};
