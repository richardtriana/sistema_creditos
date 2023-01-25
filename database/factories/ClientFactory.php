<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Client::class;

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
			'type_document' => 'CC',
			'document' => $this->faker->randomNumber(),
			'profession' => $this->faker->text(15),
			'birth_date' => $this->faker->date(),
			'email' => $this->faker->email(),
			'gender' => $this->faker->randomElement($array = ['f', 'm']),
			'phone_1' => $this->faker->phoneNumber(),
			'phone_2' => $this->faker->phoneNumber(),
			'address' => $this->faker->address(),
			'civil_status' => $this->faker->numberBetween(1, 3),
			'workplace' => $this->faker->address(),
			'occupation' => $this->faker->streetAddress(),
			'independent' => $this->faker->boolean(),
			'photo' => $this->faker->image(),
			'status' => $this->faker->boolean()
		];
	}
}
