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
            // Add is_cancelled column if it doesn't exist
            if (!Schema::hasColumn('payment_vouchers', 'is_cancelled')) {
                $table->boolean('is_cancelled')->default(false)->after('status');
            }
            
            // Add related columns if they don't exist
            if (!Schema::hasColumn('payment_vouchers', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('is_cancelled');
            }
            
            if (!Schema::hasColumn('payment_vouchers', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            }
            
            if (!Schema::hasColumn('payment_vouchers', 'cancelled_by')) {
                $table->string('cancelled_by')->nullable()->after('cancelled_at');
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
            if (Schema::hasColumn('payment_vouchers', 'is_cancelled')) {
                $table->dropColumn('is_cancelled');
            }
            
            if (Schema::hasColumn('payment_vouchers', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }
            
            if (Schema::hasColumn('payment_vouchers', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
            
            if (Schema::hasColumn('payment_vouchers', 'cancelled_by')) {
                $table->dropColumn('cancelled_by');
            }
        });
    }
};
