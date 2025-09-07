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
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id(); // standard primary key
            $table->foreignId('project_id')->constrained('projects', 'project_id')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('artifact_link')->nullable();
            $table->string('outcome_type');
            $table->string('quality_certification')->nullable();
            $table->string('commercialization_status')->nullable();
            $table->text('impact')->nullable();
            $table->date('date_achieved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcomes');
    }
};
