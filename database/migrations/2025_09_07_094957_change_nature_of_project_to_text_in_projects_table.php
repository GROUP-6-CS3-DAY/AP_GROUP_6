<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Change from ENUM to TEXT to allow free text input
            $table->text('nature_of_project')->change();
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Revert back to ENUM if needed
            $table->enum('nature_of_project', ['research', 'prototype', 'applied_work'])->change();
        });
    }
};