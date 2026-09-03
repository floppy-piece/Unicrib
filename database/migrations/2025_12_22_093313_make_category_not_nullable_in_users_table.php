<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the driver is PostgreSQL to use native safe syntax
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users ALTER COLUMN category TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE users ALTER COLUMN category SET DEFAULT 'student'");
            DB::statement("ALTER TABLE users ALTER COLUMN category SET NOT NULL");
            // Add check constraint for enum-like behavior in Postgres if needed
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_category_check");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_category_check CHECK (category IN ('student', 'landlord'))");
        } else {
            // Standard Laravel schema builder for MySQL/MariaDB/SQLite
            Schema::table('users', function (Blueprint $table) {
                $table->enum('category', ['student', 'landlord'])->default('student')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_category_check");
            DB::statement("ALTER TABLE users ALTER COLUMN category TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE users ALTER COLUMN category DROP DEFAULT");
            DB::statement("ALTER TABLE users ALTER COLUMN category DROP NOT NULL");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('category')->nullable()->change();
            });
        }
    }
};