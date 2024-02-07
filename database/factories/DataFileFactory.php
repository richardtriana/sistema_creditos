<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DataFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'file' => $this->faker->file(),
            'category' => $this->faker->word(),
            'title' => $this->faker->sentence(),
            'url_path' => $this->faker->url(),
            'description' => $this->faker->sentence(),
            'metadata' => json_encode($this->faker->array())
        ];
    }
}
