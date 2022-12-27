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

}
