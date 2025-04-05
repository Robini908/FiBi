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
        // Drop tables if they exist (in reverse order to handle foreign keys)
        Schema::dropIfExists('book_reservations');
        Schema::dropIfExists('book_loans');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('library_books');
        Schema::dropIfExists('book_authors');
        Schema::dropIfExists('book_categories');

        // 1. Book Categories Table
        Schema::create('book_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('color_code', 20)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('book_categories')
                  ->onDelete('set null');
        });

        // 2. Book Authors Table
        Schema::create('book_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('biography')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Library Books Table
        Schema::create('library_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 300)->unique();
            $table->text('description')->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('isbn13', 20)->nullable()->unique();
            $table->unsignedInteger('category_id');
            $table->string('publisher', 100)->nullable();
            $table->date('publication_date')->nullable();
            $table->string('edition', 50)->nullable();
            $table->integer('pages')->nullable();
            $table->string('language', 50)->default('English');
            $table->string('cover_image', 255)->nullable();
            $table->text('table_of_contents')->nullable();
            $table->integer('copies_available')->default(0);
            $table->integer('total_copies')->default(0);
            $table->boolean('is_reference_only')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('replacement_cost', 10, 2)->nullable();
            $table->string('dewey_decimal', 50)->nullable();
            $table->string('call_number', 50)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('book_categories')
                  ->onDelete('restrict');
        });

        // Pivot table for books and authors (many-to-many)
        Schema::create('book_author', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('author_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('book_id')
                  ->references('id')
                  ->on('library_books')
                  ->onDelete('cascade');

            $table->foreign('author_id')
                  ->references('id')
                  ->on('book_authors')
                  ->onDelete('cascade');

            $table->unique(['book_id', 'author_id']);
        });

        // 4. Book Copies Table
        Schema::create('book_copies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->string('copy_number', 50);
            $table->string('barcode', 50)->unique();
            $table->string('rfid_tag', 100)->nullable();
            $table->date('acquisition_date');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('acquisition_source', 100)->nullable();
            $table->string('shelf_location', 100)->nullable();
            $table->enum('condition', ['New', 'Good', 'Fair', 'Poor', 'Damaged'])->default('New');
            $table->text('condition_notes')->nullable();
            $table->enum('status', ['Available', 'On Loan', 'Reserved', 'Lost', 'Under Repair'])->default('Available');
            $table->date('last_inventory_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_id')
                  ->references('id')
                  ->on('library_books')
                  ->onDelete('cascade');
        });

        // 5. Book Loans Table
        Schema::create('book_loans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_copy_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('issued_by')->nullable();
            $table->unsignedInteger('received_by')->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['Active', 'Returned', 'Overdue', 'Lost', 'Damaged'])->default('Active');
            $table->decimal('fine_amount', 10, 2)->default(0.00);
            $table->boolean('is_fine_paid')->default(false);
            $table->date('fine_paid_date')->nullable();
            $table->string('fine_payment_reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->string('condition_on_return', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_copy_id')
                  ->references('id')
                  ->on('book_copies')
                  ->onDelete('cascade');

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->foreign('issued_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');

                $table->foreign('received_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });

        // 6. Book Reservations Table
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('approved_by')->nullable();
            $table->unsignedInteger('rejected_by')->nullable();
            $table->date('reservation_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Cancelled', 'Fulfilled', 'Expired'])
                  ->default('Pending');
            $table->text('notes')->nullable();
            $table->enum('priority', ['High', 'Normal', 'Low'])->default('Normal');
            $table->date('fulfillment_date')->nullable();
            $table->unsignedInteger('loan_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_id')
                  ->references('id')
                  ->on('library_books')
                  ->onDelete('cascade');

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->foreign('approved_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');

                $table->foreign('rejected_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }

            $table->foreign('loan_id')
                  ->references('id')
                  ->on('book_loans')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('book_reservations');
        Schema::dropIfExists('book_loans');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('library_books');
        Schema::dropIfExists('book_authors');
        Schema::dropIfExists('book_categories');
    }
}; 