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
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('size', 8, 2); // in sqm
            $table->string('address');
            $table->string('city')->default('Kutus');
            $table->string('area'); // mjini, ngomongo, diaspora, kutus
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->json('amenities'); // JSON array of amenities
            $table->string('property_type')->default('single-room'); // apartment, house, room, etc.
            $table->boolean('is_available')->default(true);
            $table->boolean('is_verified')->default(true);
            $table->json('images')->nullable(); // JSON array of image paths
            $table->timestamps();
            
            // Spatial index for location-based queries
            if (config('database.default') === 'mysql') {
                $table->Index(['latitude', 'longitude']);
            } else {
                $table->index(['latitude', 'longitude']);
            }
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
