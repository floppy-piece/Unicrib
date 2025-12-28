<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop old column
            if (Schema::hasColumn('users', 'category')) {
                $table->dropColumn('category');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Recreate with enum including 'admin'
            $table->enum('category', ['student', 'landlord', 'admin'])
                  ->default('student')
                  ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse: drop and recreate without admin, or set to string
            if (Schema::hasColumn('users', 'category')) {
                $table->dropColumn('category');
            }

            $table->enum('category', ['student', 'landlord'])
                  ->default('student')
                  ->after('email');
        });
    }
};
