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
        Schema::table('users', function (Blueprint $table) {
            // Add column with default value
            $table->enum('category', ['student', 'landlord'])
                  ->default('student')
                  ->after('email'); // Or wherever you want it
            });
            
            // If table has data, ensure all records have a category
            if (DB::table('users')->count() > 0) {
                DB::table('users')->whereNull('category')->update(['category' => 'student']);
            }
            
            // Now add the not null constraint
            Schema::table('users', function (Blueprint $table) {
                $table->enum('category', ['student', 'landlord'])
                    ->default('student')
                    ->nullable(false)
                    ->change();
            });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropcolumn('category');
        });
    }
};
