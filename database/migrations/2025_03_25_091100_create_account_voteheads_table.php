<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the account_voteheads table for managing account budget lines
     */
    public function up(): void
    {
        Schema::create('account_voteheads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_account_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->integer('academic_year');
            $table->tinyInteger('term');
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_voteheads');
    }
}; 