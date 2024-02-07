<?php

namespace Database\Seeders;

use App\Models\DataFile;
use Illuminate\Database\Seeder;

class DataFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DataFile::factory()
        ->count(10)
        ->create();
    }
}
