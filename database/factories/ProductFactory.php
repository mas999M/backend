<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => $name,
//            'slug' => Str::slug($name),
            'description' => $this->faker->text(),
            'category_id'=>$this->faker->numberBetween(1,10),
            'price' => $this->faker->randomFloat(2,0,1000),
            'image_path' => $this->faker->imageUrl(),
        ];
    }
}
