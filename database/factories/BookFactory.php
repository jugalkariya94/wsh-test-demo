<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'no_of_pages' => $this->faker->numberBetween(100, 500),
            'author' => $this->faker->name(),
            'wholesale_price' => $this->faker->numberBetween(100, 500)
        ];
    }
}
