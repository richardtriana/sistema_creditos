<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryToMainBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_boxes', function (Blueprint $table) {
            if (Schema::hasColumn('main_boxes', 'history')) {
                $table->json('history')->nullable()->change();
            } else {
                $table->json('history')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_boxes', function (Blueprint $table) {
            $table->dropColumn('history');
        });
    }
}
