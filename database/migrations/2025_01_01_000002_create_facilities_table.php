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
            $table->id('facility_id');
            $table->string('name');
            $table->string('location');
            $table->text('description');
            $table->string('partner_organization'); // Partner such as UniPod, UIRI, Lwera
            $table->enum('facility_type', ['lab', 'workshop', 'testing_center']);
            $table->json('capabilities'); // CNC, PCB fabrication, materials testing
            $table->timestamps();
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
