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