<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('debtor_id');
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('number_installments');
            $table->integer('number_paid_installments')->nullable()->default(0);
            $table->integer('day_limit')->nullable()->default(1);
            $table->boolean('debtor')->comment('Solo se confirma si tiene deudor');
            $table->tinyInteger('status')->default(0)->nullable()->default(0);
            $table->date('start_date');
            $table->float('interest', 20, 2)->default(3);
            $table->float('annual_interest_percentage', 20, 4)->nullable()->default(0);
            $table->float('valor_cuota', 20, 4);
            $table->float('credit_value', 20, 4);
            $table->float('paid_value', 20, 4)->nullable()->default(0);
            $table->float('capital_value', 20, 4)->nullable()->default(0);
            $table->float('interest_value', 20, 4)->nullable()->default(0);


            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');

            $table->foreign('debtor_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('credits');
    }
}
