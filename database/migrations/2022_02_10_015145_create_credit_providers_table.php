<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditProvidersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('credit_providers', function (Blueprint $table) {
			$table->id();
			$table->foreignId('credit_id');
			$table->foreignId('provider_id')->default(0);
			$table->foreignId('headquarter_id')->default(0);
			$table->foreignId('last_editor')->nullable();
			$table->double('credit_value')->default(0);
			$table->double('paid_value')->default(0);
			$table->double('pending_value')->default(0);
			$table->date('last_paid')->nullable();
			$table->json('history')->nullable();
			$table->tinyInteger('status')->default(0);

			$table->foreign('headquarter_id')
				->references('id')
				->on('headquarters');

			$table->foreign('provider_id')
				->references('id')
				->on('providers');

			$table->foreign('credit_id')
				->references('id')
				->on('credits')
				->onDelete('cascade');

			$table->foreign('last_editor')
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
		Schema::dropIfExists('credit_providers');
	}
}
