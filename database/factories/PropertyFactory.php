<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'landlord_id' => null, // will be overridden in seeder
            'title'       => $this->faker->sentence(3),
            'price'       => $this->faker->numberBetween(4000, 15000),
            'is_verified' => false,
            'description' => 'very fine house',
            'bedrooms' => 0,
            'bathrooms' =>2,
            'size'=>10,
            'address'=>'kirinyaga-kutus',
            'area'=>'Kutus',
            'amenities'=>['security'],
            'building_name'=> 'Hostel',
            //'verified_at' => null,
            // add any other required columns here (address, description, etc.)
        ];
    }
}


