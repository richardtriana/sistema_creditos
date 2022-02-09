<?php

namespace Database\Seeders;

use App\Models\Credit;
use App\Models\Installment;
use Illuminate\Database\Seeder;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Credit::factory()
        ->has(Installment::factory()->count(3))
        ->count(1)
        ->create();
    }
}
