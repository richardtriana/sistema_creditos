<?php

namespace Database\Factories;

use App\Models\Box;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoxFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Box::class;
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'headquarter_id' => $this->faker->numberBetween(1, 5),
			'initial_balance' => $this->faker->randomNumber(),
			'current_balance' => $this->faker->randomNumber(),
			'input' => $this->faker->randomNumber(),
			'output' => $this->faker->randomNumber(),
			'last_update' => $this->faker->date('Y-m-d'),
			'last_editor'  => $this->faker->numberBetween(1, 10)
		];
	}
}
