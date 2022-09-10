<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditGuaranteeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_guarantee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guarantee_id')->nullable();
            $table->foreignId('credit_id')->nullable();
            $table->date('date_add');

            $table->foreign('guarantee_id')
                ->references('id')
                ->on('guarantees');

            $table->foreign('credit_id')
                ->references('id')
                ->on('credits');
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
        Schema::dropIfExists('credit_guarantee');
    }
}
