<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $categories = [
            'Electronics',
            'Fashion & Apparel',
            'Home & Kitchen',
            'Beauty & Personal Care',
            'Health & Fitness',
            'Toys & Games',
            'Sports & Outdoors',
            'Automotive Accessories',
            'Books & Stationery',
            'Pets & Supplies',
        ];

        return [
            'name' => $this->faker->randomElement($categories),
        ];
    }
}
