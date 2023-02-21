<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boxes', function (Blueprint $table) {
            $table->float('cash')->nullable();
            $table->float('consignment_to_client')->nullable();
            $table->float('payment_to_provider')->nullable();
            $table->string('status')->nullable();
            $table->string('observations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boxes', function (Blueprint $table) {
            //
        });
    }
}
