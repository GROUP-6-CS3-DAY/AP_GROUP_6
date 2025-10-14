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
        if (!Schema::hasTable('participants')) {
            Schema::create('participants', function (Blueprint $table) {
                $table->id(); // Change to standard auto-incrementing ID
                $table->string('full_name');
                $table->string('email')->unique();
                $table->string('affiliation');
                $table->string('institution'); // Add missing institution field
                $table->string('specialization')->nullable();
                $table->boolean('cross_skill_trained')->default(false);
                $table->unsignedBigInteger('project_id')->nullable(); // Add project relationship
                $table->timestamps();
                
                // Add foreign key constraint
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
                
                // Add indexes
                $table->index(['email']);
                $table->index(['affiliation']);
                $table->index(['project_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
