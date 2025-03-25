<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration cleans up any duplicate foreign key constraints
     * that might exist in the database.
     */
    public function up(): void
    {
        // Remove the CreateFks migration from the migrations table if it exists
        DB::table('migrations')->where('migration', '2019_09_22_142514_create_fks')->delete();
        
        // Try to create the schema directory if it doesn't exist
        $schemaDir = database_path('schema');
        if (!File::exists($schemaDir)) {
            File::makeDirectory($schemaDir, 0755, true);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything in down() as we're just cleaning up migrations
    }
};
