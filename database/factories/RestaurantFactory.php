<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->company() . ' ' . $this->faker->randomElement(['Restaurant', 'Bistro', 'Grill', 'Café', 'Diner']);
        $cuisineTypes = ['Italian', 'Japanese', 'Mexican', 'Indian', 'American', 'French', 'Chinese', 'Thai', 'Mediterranean', 'Greek'];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'cuisine_type' => $this->faker->randomElement($cuisineTypes),
            'description' => $this->faker->paragraphs(3, true),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional()->safeEmail(),
            'website' => $this->faker->optional()->url(),
            'price_range' => $this->faker->randomFloat(1, 1, 5),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'accepts_reservations' => $this->faker->boolean(70),
            'opening_hours' => [
                'monday' => ['09:00-22:00'],
                'tuesday' => ['09:00-22:00'],
                'wednesday' => ['09:00-22:00'],
                'thursday' => ['09:00-22:00'],
                'friday' => ['09:00-23:00'],
                'saturday' => ['10:00-23:00'],
                'sunday' => ['10:00-21:00'],
            ],
            'facilities' => $this->faker->randomElements(
                ['WiFi', 'Parking', 'Outdoor Seating', 'Bar', 'Wheelchair Accessible', 'Live Music', 'Private Rooms'],
                $this->faker->numberBetween(2, 5)
            ),
        ];
    }
}
