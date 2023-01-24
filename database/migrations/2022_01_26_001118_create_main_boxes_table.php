<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_boxes', function (Blueprint $table) {
            $table->id();
            $table->float('initial_balance', 20,2)->default(0);
			$table->float('current_balance', 20,2)->default(0);
			$table->float('input', 20,2)->default(0);
			$table->float('output', 20,2)->default(0);
			$table->json('history')->nullable()->default(NULL);
			$table->date('last_update')->nullable();
			$table->foreignId('last_editor')->nullable();
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
        Schema::dropIfExists('main_boxes');
    }
}
