<?php

namespace Database\Seeders;

use App\Models\TypeExpense;
use Illuminate\Database\Seeder;

class TypeExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeExpense::factory()
            ->count(10)
            ->create();
    }
}
