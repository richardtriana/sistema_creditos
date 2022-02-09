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
			'headquarter_id' => 1,
			'initial_balance' => 0,
			'current_balance' => 0,
			'input' => 0,
			'output' => 0,
			'last_update' => $this->faker->date('Y-m-d'),
			'last_editor'  => 1
		];
	}
}
