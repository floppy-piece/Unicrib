<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->integer('amount'); // in kobo/cents
            $table->string('currency')->default('NGN');
            $table->string('status')->default('pending');
            $table->json('provider_payload')->nullable();
            $table->timestamps();
        });

       Schema::create('storage_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('months'); // Total months
            $table->unsignedInteger('amount'); // Total amount
            $table->unsignedInteger('paid_amount')->default(0); // Amount paid so far (1st month upfront)
            $table->string('status')->default('pending'); // pending, active, completed
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        // Drivers table for Uber-like dispatch
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->string('vehicle_model'); // e.g. Toyota Probox / Bodaboda
            $table->string('vehicle_plate', 20)->unique();
            $table->enum('status', ['available', 'busy', 'offline'])->default('available');
            $table->timestamps();
        });

        // Upgraded transports table with driver foreign key
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();

            $table->string('student_name');
            $table->string('student_phone', 20);

            $table->date('requested_date');
            $table->time('requested_time')->nullable();

            $table->dateTime('assigned_at')->nullable();
            $table->string('status')->default('pending'); // pending, driver_assigned, en_route, completed, cancelled

            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('role')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('transports');
        Schema::dropIfExists('storage_bookings');
        Schema::dropIfExists('payments');
    }
};