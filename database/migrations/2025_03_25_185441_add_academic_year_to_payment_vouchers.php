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
        Schema::table('payment_vouchers', function (Blueprint $table) {
            // Add academic_year column if it doesn't exist
            if (!Schema::hasColumn('payment_vouchers', 'academic_year')) {
                $table->integer('academic_year')->default(date('Y'));
            }
            
            // Add term column if it doesn't exist
            if (!Schema::hasColumn('payment_vouchers', 'term')) {
                $table->integer('term')->default(1);
            }
            
            // Add purpose column if it doesn't exist
            if (!Schema::hasColumn('payment_vouchers', 'purpose')) {
                $table->text('purpose')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('payment_vouchers', 'academic_year')) {
                $table->dropColumn('academic_year');
            }
            
            if (Schema::hasColumn('payment_vouchers', 'term')) {
                $table->dropColumn('term');
            }
            
            if (Schema::hasColumn('payment_vouchers', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};
