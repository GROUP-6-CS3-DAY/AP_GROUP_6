<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('outcomes');
        
        Schema::create('outcomes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('project_id'); // Change from foreignId to string
            $table->string('title');
            $table->text('description');
            $table->string('outcome_type');
            $table->string('quality_certification')->nullable();
            $table->date('date_achieved');
            $table->string('commercialization_status')->nullable();
            $table->text('impact')->nullable();
            $table->string('artifact_link')->nullable();
            $table->timestamps();

            // Remove foreign key constraint temporarily
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outcomes');
    }
};
