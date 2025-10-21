<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table since it doesn't support changing primary key types
        Schema::table('programs', function (Blueprint $table) {
            // Create a temporary table with the new structure
            DB::statement('CREATE TABLE programs_temp (
                id TEXT PRIMARY KEY,
                name TEXT NOT NULL,
                description TEXT NOT NULL,
                national_alignment TEXT NOT NULL,
                focus_areas TEXT NOT NULL,
                phases TEXT NOT NULL,
                created_at DATETIME,
                updated_at DATETIME
            )');
            
            // Copy existing data (if any) - this will fail if there's data, which is expected
            // Since we're changing from integer to string UUID, existing data can't be migrated
            
            // Drop the old table
            DB::statement('DROP TABLE programs');
            
            // Rename the temp table
            DB::statement('ALTER TABLE programs_temp RENAME TO programs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Recreate with auto-incrementing integer ID
            DB::statement('CREATE TABLE programs_temp (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT NOT NULL,
                national_alignment TEXT NOT NULL,
                focus_areas TEXT NOT NULL,
                phases TEXT NOT NULL,
                created_at DATETIME,
                updated_at DATETIME
            )');
            
            // Drop the current table
            DB::statement('DROP TABLE programs');
            
            // Rename the temp table
            DB::statement('ALTER TABLE programs_temp RENAME TO programs');
        });
    }
};
