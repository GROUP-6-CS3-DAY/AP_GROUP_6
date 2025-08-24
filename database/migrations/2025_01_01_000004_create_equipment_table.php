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
            $table->id('equipment_id');
            $table->foreignId('facility_id')->constrained('facilities', 'facility_id')->onDelete('cascade');
            $table->string('name');
            $table->text('capabilities');
            $table->text('description');
            $table->string('inventory_code');
            $table->enum('usage_domain', ['electronics', 'mechanical', 'iot']);
            $table->enum('support_phase', ['training', 'prototyping', 'testing', 'commercialization']);
            $table->timestamps();
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
