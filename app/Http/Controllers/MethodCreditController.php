<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Credit;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MethodCreditController
{

  public function calculateInstallments(Request $request)
  {
    $configurations = Company::first();

    if ($configurations->method &&  $configurations->method == "GENERAL") {

      $generalMethod = new GeneralMethodController();
      return $generalMethod->calculateInstallments($request);
    } else {

      $franchiseMethod = new FranchiseMethodController();
      return $franchiseMethod->calculateInstallments($request);
    }
  }


  public function updateInstallments($credit_id)
  {
    $configurations = Company::first();
    if ($configurations->method &&  $configurations->method == "GENERAL") {

      $generalMethod = new GeneralMethodController();
      return  $generalMethod->updateInstallments($credit_id);
    } else {

      $franchiseMethod = new FranchiseMethodController();
      return  $franchiseMethod->updateInstallments($credit_id);
    }
  }

  public function updatePaymentDate(Request $request)
  {
    $credit_id = $request->credit_id;
    $start_date = $request->start_date;
    $credit = Credit::findOrFail($credit_id);
    $credit->start_date = $start_date;
    $credit->save();

    $installments = $credit->installments()->get();
    $number_installments = count($installments);

    $fechaInicio =  $start_date;
    $mes_actual =  (date("Y-m-d", strtotime($fechaInicio . "+ 1 months")));
    $payment_date = [];

    for ($i = 0; $i < $number_installments; $i++) {
      $id_installment = $installments[$i]->id;
      $payment_date[$i] = (date("Y-m-d", strtotime($mes_actual . "+ $i months")));

      Installment::findOrFail($id_installment)->update(
        [
          'payment_date' => $payment_date[$i]
        ]
      );
    }

    return  $credit->installments()->get();
  }
}
