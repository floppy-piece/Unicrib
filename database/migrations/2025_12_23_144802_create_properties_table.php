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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained('users')->cascadeOnDelete();
            
            // Basic Info
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('size', 8, 2); // in sqm
            
            // Address & Location fields
            $table->string('address');
            $table->string('city')->default('Kutus');
            $table->string('area'); 
            $table->string('street')->nullable();
            $table->string('building_name')->nullable();
            $table->string('landmark')->nullable();
            
            // Map coordinates & geocoding
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->decimal('map_lat', 10, 8)->nullable();
            $table->decimal('map_lng', 10, 8)->nullable();
            $table->boolean('location_confirmed')->default(false);
            $table->text('formatted_address')->nullable();
            $table->json('geocoding_data')->nullable();

            // Status & Features
            $table->json('amenities');
            $table->string('property_type')->default('single-room');
            $table->boolean('is_available')->default(false); // Set default to false per later migration patches
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verification_expires_at')->nullable();
            
            // Capacity
            $table->unsignedInteger('capacity')->default(1);
            $table->unsignedInteger('booked_count')->default(0);

            $table->json('images')->nullable();
            $table->timestamps();
            
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
