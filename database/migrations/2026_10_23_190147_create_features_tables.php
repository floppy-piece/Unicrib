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
            $table->unsignedInteger('months');
            $table->unsignedInteger('amount'); // total amount in KSh
            $table->string('status')->default('pending');
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            $table->string('student_name');
            $table->string('student_phone');

            $table->date('requested_date');
            $table->time('requested_time')->nullable();

            $table->dateTime('assigned_at')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, completed, cancelled

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
        Schema::dropIfExists('transports');
        Schema::dropIfExists('storage_bookings');
        Schema::dropIfExists('payments');
    }
};