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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities', 'id')->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->string('skill_type');
            $table->timestamps();

            // Indexes for better performance
            $table->index('facility_id');
            $table->index('category');
            $table->index('skill_type');
            $table->index('name');
            
            // Unique constraint for service name per facility
            $table->unique(['facility_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
