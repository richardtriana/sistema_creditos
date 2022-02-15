<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Provider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
        'business_name' => $this->faker->company(),
        'type_document' => $this->faker->numberBetween(1,2),
        'document' => $this->faker->randomNumber(),
        'phone_1' => $this->faker->phoneNumber(),
        'phone_2' => $this->faker->phoneNumber(),
        'address' => $this->faker->address(),
        'email' => $this->faker->email()
		  ];
    }
}
