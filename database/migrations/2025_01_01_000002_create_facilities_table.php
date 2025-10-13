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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id(); // Auto-incrementing integer ID
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('facility_type');
            $table->integer('capacity')->default(0);
            $table->json('equipment_list')->nullable();
            $table->json('capabilities')->nullable();
            $table->string('availability_status')->default('available');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['facility_type']);
            $table->index(['availability_status']);
            $table->index(['name', 'location']); // For uniqueness constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
