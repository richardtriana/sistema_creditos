<?php

namespace Database\Seeders;

use App\Models\ValuationChart;
use Illuminate\Database\Seeder;

class ValuationChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=ValuationChartSeeder
        ValuationChart::insert([
            ['start' => 0, 'end' => 5, 'valuation' => 'excelente', 'color' => "#454"],
            ['start' => 6, 'end' => 10, 'valuation' => 'bueno', 'color' => "#655"],
            ['start' => 11, 'end' => 15, 'valuation' => 'regular', 'color' => "#788"],
            ['start' => 16, 'end' => 20, 'valuation' => 'malo', 'color' => "#999"]
        ]);
    }
}
