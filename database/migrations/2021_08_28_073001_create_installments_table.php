<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_id');
            $table->integer('nro_cuota');
            $table->float('value', 20, 4);
            $table->date('payment_date');
            $table->float('days_past_due')->nullable()->default(0);
            $table->float('late_interests_value', 20, 4)->nullable()->default(0);
            $table->float('interest_value', 20, 4)->nullable()->default(0);
            $table->float('capital_value', 20, 4)->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
            $table->float('paid_balance')->nullable();
            $table->date('payment_register', 20, 4)->nullable();
            $table->foreign('credit_id')
                ->references('id')
                ->on('credits')
                ->onDelete('cascade');

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
        Schema::dropIfExists('installments');
    }
}
