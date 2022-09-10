<?php

use App\Models\Credit;
use App\Models\CreditDebtor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditDebtorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_debtor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debtor_id')->nullable();
            $table->foreignId('credit_id')->nullable();

            $table->foreign('debtor_id')
                ->references('id')
                ->on('clients');

            $table->foreign('credit_id')
                ->references('id')
                ->on('credits');
                
            $table->timestamps();
        });

        $credits = Credit::get();
        foreach($credits as $credit) {

            if($credit->debtor_id) {
                CreditDebtor::create(['credit_id' => $credit->id, 'debtor_id' => $credit->debtor_id]);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_debtor');
    }
}
