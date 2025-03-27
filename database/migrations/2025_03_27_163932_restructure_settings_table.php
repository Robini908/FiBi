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
        // First drop the existing table
        Schema::dropIfExists('settings');

        // Create a new, improved settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->string('display_name');
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, array, select, date, etc.
            $table->string('group')->default('general'); // General, Academic, Finance, System, etc.
            $table->text('options')->nullable(); // For select, radio, checkbox types - stores JSON
            $table->string('description')->nullable();
            $table->boolean('is_public')->default(false); // If true, this setting is visible to non-admin users
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');

        // Recreate the original settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
};
