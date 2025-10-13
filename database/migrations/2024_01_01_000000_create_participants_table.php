<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->enum('affiliation', ['cs', 'se', 'engineering', 'other']);
            $table->enum('specialization', ['software', 'hardware', 'business'])->nullable();
            $table->enum('institution', ['scit', 'cedat', 'unipod', 'uiri', 'lwera']);
            $table->boolean('cross_skill_trained')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participants');
    }
};
