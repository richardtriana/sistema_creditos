<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Credit;
use App\Models\Entry;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PDF;

class InstallmentController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $credits = Installment::select();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
    $installment = new Installment();
    $installment->credit_id = $request['credit_id'];
    $installment->installment_number = $request['installment_number'];
    $installment->value = $request['value'];
    $installment->payment_date = $request['payment_date'];
    $installment->days_past_due = $request['days_past_due'];
    $installment->late_interests_value = $request['late_interests_value'];
    $installment->valor_pago_interes = $request['valor_pago_interes'];
    $installment->valor_pago_capital = $request['valor_pago_capital'];
    $installment->save();
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Installment  $installment
   * @return \Illuminate\Http\Response
   */
  public function show(Installment $installment)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Installment  $installment
   * @return \Illuminate\Http\Response
   */
  public function edit(Installment $installment)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Installment  $installment
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Installment $installment)
  {
    //
    $installment = new Installment();
    $installment->installment_number = $request['installment_number'];
    $installment->value = $request['value'];
    $installment->payment_date = $request['payment_date'];
    $installment->days_past_due = $request['days_past_due'];
    $installment->late_interests_value = $request['late_interests_value'];
    $installment->valor_pago_interes = $request['valor_pago_interes'];
    $installment->valor_pago_capital = $request['valor_pago_capital'];
    $installment->save();
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


  public function saveEntryInstallment(Credit $credit, $amount_receipt, $amount_paid, $no_installment, $balance,  $user_id, $quote = null)
  {
    $amount = $amount_paid;
    $amount_receipt = '$' . number_format($amount_receipt, 0, ',', '.');
    $amount_paid = '$' . number_format($amount_paid, 0, ',', '.');
    $balance = '$' . number_format($balance, 0, ',', '.');
    $type_entry = $quote ? $quote : 'Pago de cuota';

    $client = $credit->client()->first();
    if ($amount > 0) {
      $entry =  new Entry();
      $entry->headquarter_id = $credit->headquarter_id;
      $entry->user_id = $user_id;
      $entry->credit_id = $credit->id;
      $entry->description =
        "Cliente: {$client->name} {$client->last_name}\n"
        . "Documento: {$client->type_document} {$client->document}\n"
        . "#crédito: {$credit->id} \n"
        . "#cuota: {$no_installment}\n"
        . "Efectivo: {$amount_receipt}\n"
        . "Valor cancelado: {$amount_paid}\n"
        . "Regreso: {$balance} \n" .
        "Cupo crédito: {$client->maximum_credit_allowed}";
      $entry->date = date('Y-m-d');
      $entry->type_entry = $type_entry;
      $entry->price = $amount;
      $entry->save();
      return  $entry->id;
    }
  }

  public function printTable(Request $request)
  {
    $company = Company::first();

    $credit_id = $request->credit_id;
    $credit = Credit::where('id', $credit_id)->first();
    $client = $credit->client()->first();
    $installments = $credit->installments()->get();

    $details = [
      'company' => $company,
      'credit' => $credit,
      'client' => $client,
      'installments' => $installments,
      'url' => URL::to('/')
    ];

    $pdf = PDF::loadView('templates.amortization_table', $details);
    $pdf = $pdf->download('amortization_table.pdf');

    $data = [
      'status' => 200,
      'pdf' => base64_encode($pdf),
      'message' => 'Tabla generada en pdf'
    ];

    return response()->json($data);
  }

  public function correctStatusInstallments()
  {
    $installments = Installment::where('capital_value', '<=', 'paid_capital')
      ->get();

    foreach ($installments as $installment) {
      $installment = Installment::find($installment->id);
      $installment->status = 1;
      $installment->save();
    }
  }
}
