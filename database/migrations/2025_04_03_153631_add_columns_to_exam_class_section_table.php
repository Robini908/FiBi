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
        // Add columns without foreign key constraints
        if (!Schema::hasColumn('exam_class_section', 'subject_id')) {
            Schema::table('exam_class_section', function (Blueprint $table) {
                $table->unsignedBigInteger('subject_id')->nullable();
            });
        }
        
        if (!Schema::hasColumn('exam_class_section', 'venue')) {
            Schema::table('exam_class_section', function (Blueprint $table) {
                $table->string('venue')->nullable();
            });
        }
        
        if (!Schema::hasColumn('exam_class_section', 'invigilators')) {
            Schema::table('exam_class_section', function (Blueprint $table) {
                $table->text('invigilators')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns if they exist
        if (Schema::hasColumn('exam_class_section', 'subject_id')) {
            Schema::table('exam_class_section', function (Blueprint $table) {
                $table->dropColumn('subject_id');
            });
        }
        
        if (Schema::hasColumn('exam_class_section', 'venue')) {
            Schema::table('exam_class_section', function (Blueprint $table) {
                $table->dropColumn('venue');
            });
        }
        
        if (Schema::hasColumn('exam_class_section', 'invigilators')) {
            Schema::table('exam_class_section', function (Blueprint $table) {
                $table->dropColumn('invigilators');
            });
        }
    }
};
