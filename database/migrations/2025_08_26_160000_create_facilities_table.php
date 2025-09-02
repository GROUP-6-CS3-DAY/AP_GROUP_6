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
            $table->id();
            $table->string('name');
            $table->text('location');
            $table->text('description');
            $table->string('partner_organization');
            $table->enum('facility_type', [
                'workshop',
                'testing_center',
                'laboratory',
                'maker_space',
                'innovation_hub',
                'research_center'
            ]);
            $table->json('capabilities');
            $table->timestamps();

            // Indexes for better performance
            $table->index('facility_type');
            $table->index('partner_organization');
            $table->index('name');
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
