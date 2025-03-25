<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disable foreign key checks to allow for dropping tables with foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // First, drop all existing finance-related tables
        // Drop receipts first (as it references payment_records)
        Schema::dropIfExists('receipts');
        
        // Drop fee payment related tables
        Schema::dropIfExists('student_fee_payments');
        Schema::dropIfExists('student_arrears');
        Schema::dropIfExists('student_payments');
        
        // Drop finance voucher related tables
        Schema::dropIfExists('payment_vouchers');
        
        // Drop fee structure related tables
        Schema::dropIfExists('fee_allocations');
        Schema::dropIfExists('fee_structures');
        
        // Drop finance account related tables
        Schema::dropIfExists('account_voteheads');
        Schema::dropIfExists('finance_accounts');
        
        // Drop legacy payment tables
        Schema::dropIfExists('payment_records');
        Schema::dropIfExists('payments');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        //
        // Now, create all new finance tables in the correct order
        //
        
        // 1. Create finance_accounts table
        Schema::create('finance_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('initial_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
        
        // 2. Create account_voteheads table
        Schema::create('account_voteheads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_account_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->integer('academic_year');
            $table->integer('term');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('finance_account_id')
                ->references('id')
                ->on('finance_accounts')
                ->cascadeOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            // Unique constraint for name within an account, academic year and term
            $table->unique(['finance_account_id', 'name', 'academic_year', 'term'], 'unique_votehead_per_account_year_term');
        });
        
        // 3. Create fee_structures table
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('my_class_id'); // Using unsignedInteger to match my_classes.id type
            $table->integer('academic_year');
            $table->integer('term');
            $table->string('name');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('my_class_id')
                ->references('id')
                ->on('my_classes')
                ->cascadeOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            // Unique constraint for fee name within a class, academic year and term
            $table->unique(['my_class_id', 'name', 'academic_year', 'term'], 'unique_fee_per_class_year_term');
        });
        
        // 4. Create fee_allocations table
        Schema::create('fee_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_account_id');
            $table->unsignedBigInteger('votehead_id');
            $table->decimal('amount', 15, 2);
            $table->integer('academic_year');
            $table->integer('term');
            $table->text('description')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('finance_account_id')
                ->references('id')
                ->on('finance_accounts')
                ->cascadeOnDelete();
                
            $table->foreign('votehead_id')
                ->references('id')
                ->on('account_voteheads')
                ->cascadeOnDelete();
                
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
        
        // 5. Create payment_vouchers table
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->unsignedBigInteger('votehead_id');
            $table->decimal('amount', 15, 2);
            $table->string('cheque_number')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_id_number')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_address')->nullable();
            $table->text('description')->nullable();
            $table->text('purpose');
            $table->integer('academic_year');
            $table->integer('term');
            $table->date('payment_date');
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->boolean('is_cancelled')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedInteger('cancelled_by')->nullable();
            $table->string('payment_method')->default('cash');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->unsignedInteger('paid_by')->nullable();
            $table->string('attachment_path')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('votehead_id')
                ->references('id')
                ->on('account_voteheads')
                ->cascadeOnDelete();
                
            $table->foreign('cancelled_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('paid_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
        
        // 6. Create student_arrears table
        Schema::create('student_arrears', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id'); // Changed to unsignedInteger to match student_records.id
            $table->unsignedInteger('class_id'); // Using unsignedInteger to match my_classes.id type
            $table->decimal('amount', 15, 2);
            $table->integer('previous_year');
            $table->integer('previous_term');
            $table->text('description')->nullable();
            $table->boolean('is_cleared')->default(false);
            $table->timestamp('cleared_at')->nullable();
            $table->unsignedInteger('cleared_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->cascadeOnDelete();
                
            $table->foreign('class_id')
                ->references('id')
                ->on('my_classes')
                ->cascadeOnDelete();
                
            $table->foreign('cleared_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
        
        // 7. Create student_fee_payments table
        Schema::create('student_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id'); // Changed to unsignedInteger to match student_records.id
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->integer('academic_year');
            $table->integer('term');
            $table->date('payment_date');
            $table->string('payment_method');
            $table->unsignedInteger('received_by')->nullable();
            
            // Cheque payment details
            $table->string('cheque_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('cheque_date')->nullable();
            
            // Bank slip details
            $table->string('bank_slip_number')->nullable();
            $table->string('bank_branch')->nullable();
            $table->date('bank_transaction_date')->nullable();
            
            // M-Pesa details
            $table->string('mpesa_transaction_id')->nullable();
            $table->string('mpesa_phone_number')->nullable();
            $table->timestamp('mpesa_transaction_time')->nullable();
            
            // Additional information
            $table->text('notes')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedInteger('cancelled_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->cascadeOnDelete();
                
            $table->foreign('received_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('cancelled_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key checks to allow dropping tables with foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop all tables in reverse order
        Schema::dropIfExists('student_fee_payments');
        Schema::dropIfExists('student_arrears');
        Schema::dropIfExists('payment_vouchers');
        Schema::dropIfExists('fee_allocations');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('account_voteheads');
        Schema::dropIfExists('finance_accounts');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 