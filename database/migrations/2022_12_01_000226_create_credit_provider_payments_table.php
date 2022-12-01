<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditProviderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_provider_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_provider_id');
            $table->string('adviser');
            $table->double('paid_value');
            $table->text('description')->nullable();
            $table->text('evidence')->nullable();
            $table->timestamps();
            $table->foreign('credit_provider_id')->references('id')->on('credit_providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_provider_payments');
    }
}
