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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('national_alignment'); // Link to NDPIII, Roadmap, or 4IR goals
            $table->string('focus_areas'); // Domains such as IoT, automation, renewable energy
            $table->string('phases'); // Cross-Skilling, Collaboration, Technical Skills, Prototyping, Commercialization
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
