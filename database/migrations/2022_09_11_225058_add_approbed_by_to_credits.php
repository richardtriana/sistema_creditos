<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprobedByToCredits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->after('user_id');
            $table->dropForeign(['debtor_id']);
            $table->dropColumn('debtor_id');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropForeign('credits_approved_by_foreign');
            $table->dropColumn('approved_by');
        });
    }
}
