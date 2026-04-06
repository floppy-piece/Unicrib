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
        Schema::table('properties', function (Blueprint $table) {
             // For storing manual address details
             $table->string('street')->nullable();
             $table->string('building_name');
             $table->string('landmark')->nullable();
             
             // For storing map pin location
             $table->decimal('map_lat', 10, 8)->nullable();
             $table->decimal('map_lng', 10, 8)->nullable();
             $table->boolean('location_confirmed')->default(false);
             
             // For geocoding results
             $table->text('formatted_address')->nullable();
             $table->json('geocoding_data')->nullable();
         });
     }
 
     public function down()
     {
         Schema::table('properties', function (Blueprint $table) {
             $table->dropColumn([
                 'street', 'building_name', 'landmark',
                 'map_lat', 'map_lng', 'location_confirmed',
                 'formatted_address', 'geocoding_data'
             ]);
         });
     }
 
};
