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
            $table->id('outcome_ID');
            $table->unsignedBigInteger('project_ID');
            $table->string('title');
            $table->text('description');
            $table->string('artifact_link')->nullable();
            $table->string('outcome_type');
            $table->string('quality_certification')->nullable();
            $table->string('commercialization_status')->nullable();
            $table->text('impact')->nullable();
            $table->date('date_achieved');
            $table->timestamps();

            $table->foreign('project_ID')->references('project_ID')->on('projects')->onDelete('cascade');
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
