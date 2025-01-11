<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // This prevents the model from syncing to the search index
        // We would prefer that the user manually triggers the syncing with the `php artisan scout:import` command
        Restaurant::disableSearchSyncing();

        Restaurant::factory(10)->create();

        // Create some specific restaurants
        $specificRestaurants = [
            [
                'name' => 'The Golden Plate',
                'cuisine_type' => 'French',
                'price_range' => 4.5,
                'rating' => 4.8,
                'city' => 'New York',
                'state' => 'NY',
            ],
            [
                'name' => 'Sushi Master',
                'cuisine_type' => 'Japanese',
                'price_range' => 3.5,
                'rating' => 4.6,
                'city' => 'Los Angeles',
                'state' => 'CA',
            ],
            [
                'name' => 'Mama\'s Kitchen',
                'cuisine_type' => 'Italian',
                'price_range' => 2.5,
                'rating' => 4.7,
                'city' => 'Chicago',
                'state' => 'IL',
            ],
        ];

        foreach ($specificRestaurants as $restaurant) {
            Restaurant::factory()->create($restaurant);
        }

        Restaurant::enableSearchSyncing();
    }
}
