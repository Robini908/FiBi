<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateDormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dorms', function (Blueprint $table) {
            // Updating or adding the 'name' column
            if (!Schema::hasColumn('dorms', 'name')) {
                $table->string('name')->nullable();
            }

            // Updating or adding the 'capacity' column
            if (!Schema::hasColumn('dorms', 'capacity')) {
                $table->integer('capacity')->nullable();
            }

            // Adding the 'user_id' column if it does not exist
            if (!Schema::hasColumn('dorms', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            // Updating or adding the 'session' column
            if (!Schema::hasColumn('dorms', 'session')) {
                $table->string('session')->nullable();
            }

            // Adding foreign key constraint to user_id if it exists
            if (Schema::hasColumn('dorms', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dorms', function (Blueprint $table) {
            // Check if the foreign key exists before trying to drop it
            if (Schema::hasColumn('dorms', 'user_id') && $this->foreignKeyExists('dorms', 'dorms_user_id_foreign')) {
                $table->dropForeign(['user_id']);
            }

            // Drop columns if they exist
            $columnsToDrop = [];
            
            if (Schema::hasColumn('dorms', 'user_id')) {
                $columnsToDrop[] = 'user_id';
            }
            
            if (Schema::hasColumn('dorms', 'session')) {
                $columnsToDrop[] = 'session';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
    
    /**
     * Check if a foreign key exists on a table
     * 
     * @param string $table
     * @param string $foreignKey
     * @return bool
     */
    private function foreignKeyExists($table, $foreignKey)
    {
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND CONSTRAINT_NAME = '{$foreignKey}'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        return count($constraints) > 0;
    }
}
