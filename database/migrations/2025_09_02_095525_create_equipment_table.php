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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id('equipment_ID');
            $table->unsignedBigInteger('facility_ID');
            $table->string('name');
            $table->text('capabilities');
            $table->text('description');
            $table->string('inventory_code');
            $table->string('usage_domain');
            $table->string('support_phase');
            $table->timestamps();

            $table->foreign('facility_ID')->references('facility_ID')->on('facilities')->onDelete('cascade');
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
