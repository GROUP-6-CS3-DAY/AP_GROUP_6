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
            $table->id();
            $table->foreignId('project_id')->constrained('projects', 'project_id')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('participants', 'participant_id')->onDelete('cascade');
            $table->enum('role_on_project', ['student', 'lecturer', 'contributor']);
            $table->enum('skill_role', ['developer', 'engineer', 'designer', 'business_lead']);
            $table->timestamps();

            // Ensure unique participant per project
            $table->unique(['project_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_participants');
    }
};
