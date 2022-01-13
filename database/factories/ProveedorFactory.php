<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proveedor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
        'name' => $this->faker->firstName(),
        'last_name' => $this->faker->lastName(),
        'type_document' => $this->faker->numberBetween(1,2),
        'document' => $this->faker->randomNumber(),
        'phone_1' => $this->faker->phoneNumber(),
        'phone_2' => $this->faker->phoneNumber(),
        'address' => $this->faker->address(),
        'email' => $this->faker->email()
		  ];
    }
}
