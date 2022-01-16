<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Installment;
use Illuminate\Database\Seeder;

class InstallmentSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Installment::factory()
			->count(20)
			->create();
	}
}
