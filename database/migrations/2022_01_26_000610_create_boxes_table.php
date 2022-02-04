<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('boxes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('headquarter_id')->nullable();
			$table->float('initial_balance', 20, 2)->default(0);
			$table->float('current_balance', 20, 2)->default(0);
			$table->float('input', 20, 2)->default(0);
			$table->float('output', 20, 2)->default(0);
			$table->json('history')->nullable();
			$table->date('last_update')->nullable();
			$table->foreignId('last_editor')->nullable();

			$table->foreign('headquarter_id')
				->references('id')
				->on('headquarters')
				->onDelete('cascade');

			$table->foreign('last_editor')
				->references('id')
				->on('users');
				
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('boxes');
	}
}
