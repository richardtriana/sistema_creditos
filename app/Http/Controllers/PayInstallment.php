/*
    * $id = id de la cuota a pagar
    * $amount =  Viene desde controlador 
    * $request = viene desde petición de cabeceras
    * Esto se usa en caso de que se pague una sola cuota
    * o multiples cuotas desde CreditController para abono a capital
    */

  // Pagar cuota por abono o individual 
  public function payInstallment(Credit $credit, $amount = null, Request $request)
  {

    $quote = $request->quote_id != null || $request->quote_id != 0 ? true : false;
    $user_id = $request->user()->id;

    if ($amount == null) {
      //Valor de la cuota o abono
      $amount = $request->amount;
    }

    $amount_receipt = $amount;
    $now = now();
    $status = 0;

    // DB::enableQueryLog(); // Enable query log
    $installment = $credit->installments();
    if (!$quote) {
      $installment = $installment->whereDate('payment_date', '<=', $now);
    }

    $installment = $installment->where(function ($query) {
      $query
        ->whereRaw('((paid_capital) +0.1) < ((capital_value))')
        ->orWhereNull('paid_balance');
    })
      ->first();

    // dd(DB::getQueryLog()); // Show results of log

    if (($installment) == null && !$quote) {
      $installment = $credit->installments()
        ->where('payment_date', '>=', $now)
        ->first();
    }

    $amount_capital = 0;
    $interest = 0;
    $balance = 0;
    $days_past_due = 0;
    $no_installment = $installment->installment_number;

    //Se verifica el saldo restante del crédito
    if (!$quote) {
      $balance_credit = ($credit->credit_value - $credit->capital_value);
      $balance_credit  = $installment->paid_capital > 0 ? $balance_credit : $balance_credit + $installment->interest_value;
      if ($balance_credit <= $amount) {
        $amount = $balance_credit;
        $balance = $amount_receipt - $amount;
      }
    }

    $payment_date = Carbon::createFromFormat('Y-m-d', $installment->payment_date);

    if ($credit->credit_value > $credit->capital_value) {
      //Cuando el cliente paga a tiempo
      if ($payment_date >= $now) {
        //primer pago
        $status = 1;
        $amount_capital = $amount -  $installment->interest_value; //ok
        $interest = $installment->interest_value;
        if ($installment->paid_balance == null || $installment->paid_balance == 0) {
          if ($amount_capital < $installment->capital_value) {
            $status = 0;
          }
        }
        if (!$quote) {
          //cuando se ha realizado abono
          if ($installment->paid_balance > 0) {
            $amount_capital = $amount;
            $interest = 0;
          }
        }
      }

      //Cuando el cliente paga después de la fecha programada
      if ($payment_date < $now) {

        $days_past_due = $installment->days_past_due ? $installment->days_past_due :  $now->diffInDays($payment_date);
        $day_value_default = $installment->interest_value / 30;
        $late_interests_value = $day_value_default * $days_past_due;
        $installment->days_past_due  = $days_past_due > 30 ? 30 : $days_past_due;
        $installment->late_interests_value  = $late_interests_value;
        $interest = $installment->interest_value + $late_interests_value;

        $amount_capital = $amount -  $interest; //ok
        $status = $amount + 1 < ($installment->value + $late_interests_value) ? 0 : 1;
        $balance -= $late_interests_value;
        $balance = $balance < 0 ?? (int) 0;
      }

      $installment->paid_balance +=  ($amount_capital + $interest);
      $installment->paid_capital += $amount_capital;
      $installment->status  = $quote ? 1 : $status;
      $installment->payment_register = date('Y-m-d');

      if (!$installment->save()) {
        return false;
      }
      $credit_paid = new CreditController;
      $credit_paid->updateValuesCredit($request, $credit->id, $amount, $amount_capital, $interest);
      $entry_id =  $this->saveEntryInstallment($credit, $amount_receipt, $amount_capital + $interest, $no_installment, $balance, $user_id, $quote);
    }
    if (!$quote) {
      $this->updateInstallments($credit->id);
    }

    return ['balance' => $balance, 'no_installment' => $no_installment, 'entry_id' => $entry_id];
  }

  
  // Pagar cuota por abono o individual 
  public function payInstallment(Credit $credit, Request $request)
  {
    $quote = $request->quote_id != null || $request->quote_id != 0 ? true : false;
    $user_id = $request->user()->id;

    //Valor de la cuota o abono
    $now = now();
    $late_interest_pending = $request->late_interest_pending??0;
    $amount = $request->amount;
    $capital = (float)0;
    $interest = (float) 0;
    $late_interest = (float) 0;
    $balance = (float) $amount;
    $step = 0;
    $status = 0;

    // DB::enableQueryLog(); // Enable query log
    $installment = $credit->installments()
      ->where(function ($query) use ($late_interest_pending) {
        $query
          ->whereRaw("((paid_balance - paid_capital)+0.1) < ((interest_value)+$late_interest_pending)")
          ->orWhereNull('paid_balance');
      })->first();

    if (!$installment) {
      $installment = $credit->installments()
        ->where(function ($query) {
          $query
            ->whereRaw('((paid_capital) +0.1) < ((capital_value))');
        })->first();
    }
    if (!$installment) {
      $installment = $credit->installments()
        ->where(function ($query) {
          $query
            ->whereRaw('(paid_balance-paid_capital-interest_value)>0.1');
        })->first();
    }

    $no_installment = $installment->installment_number;
    $payment_date = Carbon::createFromFormat('Y-m-d', $installment->payment_date);

    if ($credit->credit_value > $credit->capital_value) {
      //Cuando el cliente paga a tiempo
      if ($payment_date >= $now) {
        // Primer pago
        if ($installment->capital_value >= (float)$installment->paid_capital) {
          $helpPendingCapital = $installment->capital_value - (float)$installment->paid_capital;
          if ($balance > $helpPendingCapital) {
            $capital = $helpPendingCapital;
            $balance = $balance - $capital;
            $helpPendingInterest = $installment->interest_value - ($installment->paid_balance - $installment->paid_capital);
            if ($balance >= $helpPendingInterest) {
              $interest =  $helpPendingInterest;
              $balance = $balance - $interest;
              $status = 1;
            } else {
              $interest = $balance;
              $balance = $balance - $interest;
            }
          } else {
            $capital = $balance;
            $balance = $balance - $capital;
          }
        }
      }

      //Cuando el cliente paga después de la fecha programada
      if ($payment_date < $now) {
        $days_past_due = $installment->days_past_due ? $installment->days_past_due :  $now->diffInDays($payment_date);
        $day_value_default = $installment->interest_value / 30;
        $late_interests_value = $day_value_default * $days_past_due;
        $installment->days_past_due  = $days_past_due;

        if ($installment->paid_capital >= $installment->capital_value) {

          $paidInterest = ($installment->paid_balance - $installment->paid_capital);
          if ($paidInterest >= $installment->interest_value) {
            // if (($paidInterest - $installment->interest_value) > 0) {
              $helpPaidLateIint =  $late_interests_value - ($paidInterest - $installment->interest_value);
              if ($balance >= $helpPaidLateIint) {
                $late_interest = $helpPaidLateIint;
                $balance = $balance - $late_interest;
                $status = 1;
              } else {
                $late_interest = $balance;
                $balance = $balance - $late_interest;
              }
            // }
          } else {

            if ($paidInterest > $amount) {
              $interest = $amount;
              $balance = $balance - $interest;
            } else {
              $interest = $paidInterest;
              $balance = $balance - $interest;
              if ($balance >= $late_interests_value) {
                $late_interest = $late_interests_value;
                $balance = $balance - $late_interest;
                $status = 1;
              } else {
                $late_interest = $balance;
                $balance = $balance - $late_interest;
              }
            }
          }
        } else {
          $paidInterest = ($installment->paid_balance - $installment->paid_capital);
          if ($amount < ($installment->capital_value - $installment->paid_capital)) {
            $capital = $amount;
            $balance = $balance - $capital;
          } else {
            $capital =  ($installment->capital_value - $installment->paid_capital);
            $balance = $balance - $capital;
            if (($paidInterest) < $installment->interest_value) {
              if ($balance > $installment->interest_value) {
                $interest = $installment->interest_value - $paidInterest;
                $balance = $balance - $interest;

                if ($balance >= $late_interests_value) {
                  $late_interest = $late_interests_value;
                  $balance = $balance - $late_interest;
                  $status = 1;
                } else {
                  $late_interest = $balance;
                  $balance = $balance - $late_interest;
                }
              } else {
                $interest = $balance;
                $balance = $balance - $interest;
              }
            }
          }
        }
      }

      $installment->payment_register = date('Y-m-d');
      $installment->paid_balance +=  ($capital + $interest + $late_interest);
      $installment->paid_capital += $capital;
      $installment->late_interests_value += $late_interest;
      $installment->status = $status;
      if (!$installment->save()) {
        return false;
      }
      $paidValue =  $capital + $interest + $late_interest;
      if ((int)($amount) != (int)($installment->value + $late_interests_value)) {
        $subject = "Abono a cuota";
      } else {
        $subject = "Pago de cuota";
      }
      $credit_paid = new CreditController;
      $credit_paid->updateValuesCredit($request, $credit->id, $paidValue, $capital, $interest);
      $entry_id =  $this->saveEntryInstallment($credit, $amount, $capital + $interest + $late_interest, $no_installment, $balance, $user_id, $subject);
    }
    // return [
    //   '$installment' => $installment,
    //   'balance' => $balance,
    //   'amount' => $amount,
    //   'capital' => $capital,
    //   'interest' => $interest,
    //   'step' => $step,
    //   'status' => $status,
    //   'late_interest' => $late_interest ?? 0,
    //   'late_interests_value' => $late_interests_value ?? 0,
    //   'helpPaidLateIint' => $helpPaidLateIint ?? 0,
    //   'paidInterest' => $paidInterest ?? 0
    // ];
    return ['balance' => $balance, 'no_installment' => $no_installment, 'entry_id' => $entry_id];
  }


  public function payCredit(Credit $credit, Request $request)
  {
    $configurations = Company::first();
    $generalMethod = new GeneralMethodController();
    $franchiseMethod = new FranchiseMethodController();
    $user_id = $request->user()->id;

    //Valor de la cuota o abono
    $amount = $request->amount;
    $amount_receipt = $amount;
    $now = now();
    $status = 0;

    // DB::enableQueryLog(); // Enable query log
    $installment = $credit->installments()->whereDate('payment_date', '<=', $now)
      ->where(function ($query) {
        $query
          ->whereRaw('((paid_capital) +0.1) < ((capital_value))')
          ->orWhereNull('paid_balance');
      })->first();

    // dd(DB::getQueryLog()); // Show results of log

    if (($installment) == null) {
      $installment = $credit->installments()
        ->where('payment_date', '>=', $now)
        ->first();
    }

    $amount_capital = 0;
    $interest = 0;
    $balance = 0;
    $days_past_due = 0;
    $no_installment = $installment->installment_number;

    //Se verifica el saldo restante del crédito
    $balance_credit = ($credit->credit_value - $credit->capital_value);
    $balance_credit  = $installment->paid_capital > 0 ? $balance_credit : $balance_credit + $installment->interest_value;

    if ($balance_credit <= $amount) {
      $amount = $balance_credit;
      $balance = $amount_receipt - $amount;
    }

    $payment_date = Carbon::createFromFormat('Y-m-d', $installment->payment_date);

    if ($credit->credit_value > $credit->capital_value) {
      //Cuando el cliente paga a tiempo
      if ($payment_date >= $now) {
        //primer pago
        $status = 1;
        $amount_capital = $amount -  $installment->interest_value; //ok
        $interest = $installment->interest_value;
        if ($installment->paid_balance == null || $installment->paid_balance == 0) {
          if ($amount_capital < $installment->capital_value) {
            $status = 0;
          }
        }
        //cuando se ha realizado abono
        if ($installment->paid_balance > 0) {
          $amount_capital = $amount;
          $interest = 0;
        }
      }

      //Cuando el cliente paga después de la fecha programada
      if ($payment_date < $now) {
        $days_past_due = $installment->days_past_due ? $installment->days_past_due :  $now->diffInDays($payment_date);
        $day_value_default = $installment->interest_value / 30;
        $late_interests_value = $day_value_default * $days_past_due;
        $installment->days_past_due  = $days_past_due;
        $installment->late_interests_value  = $late_interests_value;
        $interest = $installment->interest_value + $late_interests_value;

        $amount_capital = $amount -  $interest; //ok
        $status = $amount + 1 < ($installment->value + $late_interests_value) ? 0 : 1;
        $balance -= $late_interests_value;
        $balance = $balance < 0 ?? (int) 0;
      }

      $installment->paid_balance +=  ($amount_capital + $interest);
      $installment->paid_capital += $amount_capital;
      $installment->status  = $status;
      $installment->payment_register = date('Y-m-d');


      if (!$installment->save()) {
        return false;
      }
      $credit_paid = new CreditController;
      $credit_paid->updateValuesCredit($request, $credit->id, $amount, $amount_capital, $interest);
      $entry_id =  $this->saveEntryInstallment($credit, $amount_receipt, $amount_capital + $interest, $no_installment, $balance, $user_id, 'Abono a crédito');
    }

    if ($configurations->method &&  $configurations->method == "GENERAL") {
      $generalMethod->updateInstallments($credit->id);
    } else {
      $franchiseMethod->updateInstallments($credit->id);
    }

    return ['balance' => $balance, 'no_installment' => $no_installment, 'entry_id' => $entry_id];
  }


  public function payCredit(Credit $credit, Request $request)
  {
    $configurations = Company::first();
    $generalMethod = new GeneralMethodController();
    $franchiseMethod = new FranchiseMethodController();
    $user_id = $request->user()->id;

    //Valor de la cuota o abono
    $amount = $request->amount;

    $late_interest_pending = $request->late_interest_pending ?? 0;
    $balance = (float) $request->amount;
    $capital = 0;
    $interest = 0;
    $days_past_due = 0;
    $late_interest = 0;
    $late_interests_value = 0;
    $step = 0;
    $adittionalBalance = 0;

    $now = now();
    $status = 0;

    // DB::enableQueryLog(); // Enable query log
    $installment = $credit->installments()
      ->whereDate('payment_date', '<=', $now)
      ->where(function ($query) use ($late_interest_pending) {
        $query
          ->whereRaw("((paid_balance - paid_capital)+0.1) < ((interest_value)+$late_interest_pending)")
          ->orWhereNull('paid_balance');
      })->first();

    if (!$installment) {
      $installment = $credit->installments()
        ->where('payment_date', '>=', $now)
        ->first();
    }

    $no_installment = $installment->installment_number;

    //Se verifica el saldo restante del crédito

    $paidTotalCredit =  $credit->installments()->selectRaw("
    SUM(interest_value) AS interest_value,
      SUM(paid_capital) AS paid_capital,
      SUM(paid_balance) AS paid_balance
    ")->where('paid_balance', '>', 0)->first();

    $totalCredit =  $credit->installments()->selectRaw("
      SUM(interest_value) AS interest_value
    ")->first();

    $balance_credit = (($credit->credit_value + $totalCredit->interest_value) - ($paidTotalCredit->paid_capital + $paidTotalCredit->interest_value));

    if ($balance_credit <= $amount) {
      $amount = $balance_credit;
      $balance = $balance_credit > 0 ? $balance_credit : 0;
    }

    $payment_date = Carbon::createFromFormat('Y-m-d', $installment->payment_date);

    if ($balance_credit > 0) {
      //Cuando el cliente paga a tiempo
      $step = 10;

      $helpPendingCapital = $installment->paid_capital >= $installment->capital_value ? 0 : $installment->capital_value - $installment->paid_capital;
      if ($balance >= $helpPendingCapital) {
        $step = 1;
        $capital = $helpPendingCapital;
        $balance = $balance - $capital;

        $helpAdditionalCapital = $installment->paid_balance - $installment->capital_value;

        if ($helpAdditionalCapital) {
          if ($helpAdditionalCapital >= $installment->interest_value) {
            $helpPaidInterest = $installment->interest_value;
            $helpPendingInterest = 0;
          } else {
            $helpPaidInterest = $installment->interest_value - ($installment->paid_balance - $installment->paid_capital);
            $helpPendingInterest = $installment->interest_value - $helpPaidInterest;
          }
        } else {
          $helpPaidInterest = 0;
          $helpPendingInterest = $installment->interest_value;
        }

        if ($balance >= $helpPendingInterest) {
          $step = 2;

          $interest = $helpPendingInterest;
          $balance = $balance - $interest;
          $status = 1;

          //Cuando el cliente paga después de la fecha programada
          if ($payment_date < $now) {
            $step = 3;

            $days_past_due = $installment->days_past_due ? $installment->days_past_due :  $now->diffInDays($payment_date);
            $day_value_default = $installment->interest_value * $configurations->late_interest_day;
            $late_interests_value = $day_value_default * $days_past_due;
            $installment->days_past_due  = $days_past_due;
            $installment->late_interests_value  = $late_interests_value;

            $helpPaidLateInt = $helpPaidInterest > $installment->interest_value ? $helpPaidInterest - $installment->interest_value : 0;
            $helpPendingLateIint =  $late_interests_value - $helpPaidLateInt;

            if ((int)$balance >= (int) $helpPendingLateIint) {
              $step = 4;

              $late_interest = $helpPendingLateIint;
              $balance = $balance - $late_interest;
              $status = 1;
            } else {
              $step = 5;
              $late_interest = $balance;
              $balance = $balance - $late_interest;
              $status = 0;
            }
          }

          if ($balance > 0) {
            $adittionalBalance = $balance;
            $capital += $balance;
            $status = 1;
          }
        } else {
          $interest = $balance;
          $balance = $balance - $interest;
        }
      } else {
        $capital = $helpPendingCapital;
        $balance = $balance - $capital;
      }

      $installment->paid_balance +=  ($capital + $interest + $late_interest);
      $installment->paid_capital += $capital;
      $installment->capital_value += $adittionalBalance;
      $installment->status  = $status;
      $installment->payment_register = date('Y-m-d');


    //   if (!$installment->save()) {
    //     return false;
    //   }
    //   $credit_paid = new CreditController;
    //   $credit_paid->updateValuesCredit($request, $credit->id, $amount, $capital, $interest);
    //   $entry_id =  $this->saveEntryInstallment($credit, $amount, $capital + $interest, $no_installment, $balance, $user_id, 'Abono a crédito');
    }

    // if ($configurations->method &&  $configurations->method == "GENERAL") {
    //   $generalMethod->updateInstallments($credit->id);
    // } else {
    //   $franchiseMethod->updateInstallments($credit->id);
    // }

    // return [
    //   'balance' => $balance,
    //   'no_installment' => $no_installment,
    //   'entry_id' => $entry_id,
    //   'installment' => $installment
    // ];

    return [
      'installment' => $installment,
      'no_installment' => $no_installment,
      'balance' => $balance,
      'amount' => $amount,
      'capital' => $capital,
      'interest' => $interest,
      'step' => $step,
      'status' => $status,
      // 'entry_id' => $entry_id,
      'late_interest' => $late_interest ?? null,
      'late_interests_value' => $late_interests_value ?? null,
      'helpPendingLateIint' => $helpPendingLateIint ?? null,
      'paidInterest' => $paidInterest ?? null,
      'balance_credit' => $balance_credit ?? null
    ];
  }
