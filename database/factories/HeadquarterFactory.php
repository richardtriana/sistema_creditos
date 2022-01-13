<?php

namespace Database\Factories;

use App\Models\Headquarter;
use Illuminate\Database\Eloquent\Factories\Factory;

class HeadquarterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Headquarter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'headquarter' => $this->faker->name(),
            'status' => $this->faker->boolean(),
            'address' => $this->faker->address(),
            'nit' => $this->faker->ean13(),
            'email' => $this->faker->email(),
            'legal_representative' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'pos_printer' => 'POS-80'
        ];
    }
}
