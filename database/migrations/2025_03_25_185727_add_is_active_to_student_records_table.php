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
        Schema::table('student_records', function (Blueprint $table) {
            // Add is_active column if it doesn't exist
            if (!Schema::hasColumn('student_records', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
        });
        
        // Update the is_active column based on the status column
        DB::statement("UPDATE student_records SET is_active = (status = 'active' OR status = 'verified')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Drop is_active column if it exists
            if (Schema::hasColumn('student_records', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
