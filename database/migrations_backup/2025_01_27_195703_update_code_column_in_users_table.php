<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCodeColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if the index exists before trying to drop it
            $indexExists = $this->indexExists('users', 'users_code_unique');
            if ($indexExists) {
                $table->dropUnique('users_code_unique');
            }

            // Modify the 'code' column to be nullable and unique
            $table->string('code', 100)->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if the index exists before trying to drop it
            $indexExists = $this->indexExists('users', 'users_code_unique');
            if ($indexExists) {
                $table->dropUnique('users_code_unique');
            }

            // Revert the column to its original state (not nullable and not unique)
            $table->string('code', 100)->nullable(false)->change();
        });
    }

    /**
     * Check if an index exists
     * 
     * @param string $table
     * @param string $index
     * @return bool
     */
    private function indexExists($table, $index)
    {
        $indices = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$index}'");
        return count($indices) > 0;
    }
}