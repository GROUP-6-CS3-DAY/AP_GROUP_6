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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->string('name');
            $table->json('capabilities');
            $table->text('description');
            $table->string('inventory_code')->unique();
            $table->enum('usage_domain', [
                'electronics',
                'mechanical',
                'iot',
                'software',
                'renewable_energy',
                'automation',
                'materials',
                'biomedical'
            ]);
            $table->enum('support_phase', [
                'training',
                'prototyping',
                'testing',
                'commercialization',
                'research'
            ]);
            $table->timestamps();

            // Indexes for better performance
            $table->index('facility_id');
            $table->index('usage_domain');
            $table->index('support_phase');
            $table->index('inventory_code');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
