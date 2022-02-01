<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function (Blueprint $table) {
			$table->id();
			$table->foreignId('headquarter_id');
			$table->foreignId('user_id');
			$table->string('description');
			$table->date('date');
			$table->string('type_output');
			$table->double('price');

			$table->foreign('headquarter_id')
				->references('id')
				->on('headquarters')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')
				->on('users');

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
		Schema::dropIfExists('expenses');
	}
}
