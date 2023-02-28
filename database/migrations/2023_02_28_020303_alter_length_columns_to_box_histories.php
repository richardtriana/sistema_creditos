<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLengthColumnsToBoxHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('box_histories', function (Blueprint $table) {
            $table->float('value', 25, 2)->nullable()->change();
            $table->float('cash', 25, 2)->nullable()->change();
            $table->float('consignment_to_client', 25, 2)->nullable()->change();
            $table->float('payment_to_provider', 25, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('box_histories', function (Blueprint $table) {
            //
        });
    }
}
