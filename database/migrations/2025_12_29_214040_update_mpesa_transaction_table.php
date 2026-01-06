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
        Schema::table('mpesa_transaction', function (Blueprint $table) {
             // What was the payment for? e.g. LANDLORD_VERIFICATION, STUDENT_VERIFICATION
             if (! Schema::hasColumn('mpesa_transaction', 'purpose')) {
                $table->string('purpose')->nullable()->index();
            }

            // MpesaReceiptNumber from callback, if you don’t already have it
            if (! Schema::hasColumn('mpesa_transaction', 'mpesa_receipt')) {
                $table->string('mpesa_receipt')->nullable()->index();
            }

            // Raw callback JSON
            if (! Schema::hasColumn('mpesa_transaction', 'raw_callback')) {
                $table->json('raw_callback')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpesa_transaction', function (Blueprint $table) {
            if (Schema::hasColumn('mpesa_transaction', 'purpose')) {
                $table->dropColumn('purpose');
            }
            if (Schema::hasColumn('mpesa_transaction', 'mpesa_receipt')) {
                $table->dropColumn('mpesa_receipt');
            }
            if (Schema::hasColumn('mpesa_transaction', 'raw_callback')) {
                $table->dropColumn('raw_callback');
            }
        });
    }
};


