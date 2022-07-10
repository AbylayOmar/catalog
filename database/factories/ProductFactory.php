<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'category_id' => random_int(1, 9),
            'price' => random_int(1, 100),
            'description' => $this->faker->text,
            'slug' => Str::slug($this->faker->word),
        ];
    }
}
