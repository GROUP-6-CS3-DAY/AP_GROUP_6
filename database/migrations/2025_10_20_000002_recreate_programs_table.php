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
        // Drop the existing programs table
        Schema::dropIfExists('programs');
        
        // Recreate with string UUID primary key
        Schema::create('programs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->text('description');
            $table->string('national_alignment');
            $table->string('focus_areas');
            $table->string('phases');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
        
        // Recreate with auto-incrementing ID
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('national_alignment');
            $table->string('focus_areas');
            $table->string('phases');
            $table->timestamps();
        });
    }
};
