<?php

namespace Database\Factories;

use App\Models\Pago;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagoFactory extends Factory
{

    protected $model = Pago::class;

    public function definition()
    {
      return [
        'tipo_deuda' => $this->faker->numberBetween(1,3),
        'id_deuda' => $this->faker->numberBetween(1,3),
        'valor_pago' => $this->faker->randomFloat(2,15000,20000),
        'nro_cuota' => $this->faker->numberBetween(1,14),
        'interest_value' => $this->faker->randomFloat(2,15000,20000),
        'capital_value' => $this->faker->randomFloat(2,15000,20000),
        'id_tercero' => $this->faker->numberBetween(1,3),
        'payment_date' => $this->faker->date()
		  ];
    }
}
