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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('payment_vouchers', 'academic_year')) {
                $table->integer('academic_year')->default(date('Y'));
            }
            
            if (!Schema::hasColumn('payment_vouchers', 'term')) {
                $table->integer('term')->default(1);
            }
            
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
            $columnsToDrop = [];
            
            // Check if columns exist before attempting to drop them
            if (Schema::hasColumn('payment_vouchers', 'academic_year')) {
                $columnsToDrop[] = 'academic_year';
            }
            
            if (Schema::hasColumn('payment_vouchers', 'term')) {
                $columnsToDrop[] = 'term';
            }
            
            if (Schema::hasColumn('payment_vouchers', 'purpose')) {
                $columnsToDrop[] = 'purpose';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
