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

  public function payInstallment(Credit $credit, $amount = null, Request $request)
  {
    $configurations = Company::first();

    if ($configurations->method &&  $configurations->method == "GENERAL") {

      $generalMethod = new GeneralMethodController();
      return $generalMethod->payInstallment($credit, $amount,  $request);
    } else {
      $franchiseMethod = new FranchiseMethodController();
      return  $franchiseMethod->payInstallment($credit, $amount,  $request);
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

  public function reversePaymentInstallment(Request $request, $id)
  {
    $configurations = Company::first();

    if ($configurations->method &&  $configurations->method == "GENERAL") {

      $generalMethod = new GeneralMethodController();
      return $generalMethod->reversePaymentInstallment($request, $id);
    } else {

      $franchiseMethod = new FranchiseMethodController();
      return $franchiseMethod->reversePaymentInstallment($request, $id);
    }
  }
}
