<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->double('credit_requested')->nullable()->after('description');
            $table->double('doc_acc_imp')->default(0)->after('credit_requested');
            $table->double('initial_quota')->default(0)->after('doc_acc_imp');
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
            $table->dropColumn('credit_requested');
            $table->dropColumn('doc_acc_imp');
            $table->dropColumn('initial_quota');
        });
    }
}
