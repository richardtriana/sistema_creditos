<?php

namespace Database\Seeders;

use App\Models\TypeEntry;
use Illuminate\Database\Seeder;

class TypeEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeEntry::factory()
            ->count(10)
            ->create();
    }
}
