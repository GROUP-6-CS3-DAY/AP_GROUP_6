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
            $table->id('service_id');
            $table->foreignId('facility_id')->constrained('facilities', 'facility_id')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->enum('category', [
                'machining',
                'testing',
                'training',
                'prototyping',
                'fabrication',
                'analysis',
                'consultation'
            ]);
            $table->enum('skill_type', [
                'hardware',
                'software',
                'integration',
                'business',
                'research'
            ]);
            $table->timestamps();

            // Indexes for better performance
            $table->index('facility_id');
            $table->index('category');
            $table->index('skill_type');
            $table->index('name');
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