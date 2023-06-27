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
    $configurations = Company::first();

    //Valor de la cuota o abono
    $now = now();
    $late_interest_pending = $request->late_interest_pending ?? 0;
    $amount = $request->amount;
    $capital = (float)0;
    $interest = (float) 0;
    $late_interest = (float) 0;
    $balance = (float) $amount + 0;
    $step = 0;
    $status = 0;
    $late_interests_value = 0;

    // DB::enableQueryLog(); // Enable query log
    $installment = $credit->installments()
      ->where(function ($query) use ($late_interest_pending) {
        $query
          ->whereRaw("((paid_balance - paid_capital)) < ((interest_value)+$late_interest_pending)")
          ->orWhereNull('paid_balance');
      })
      ->where('status', 0)
      ->first();

    if (!$installment) {
      $installment = $credit->installments()
        ->where(function ($query) {
          $query
            ->whereRaw('((paid_capital) +0.1) < ((capital_value))');
        })
        ->where('status', 0)
        ->first();
    }
    if (!$installment) {
      $installment = $credit->installments()
        ->where(function ($query) {
          $query
            ->whereRaw('(paid_balance-paid_capital-interest_value)>0.1');
        })
        ->where('status', 0)
        ->first();
    }

    $no_installment = $installment->installment_number;
    $payment_date = Carbon::createFromFormat('Y-m-d', $installment->payment_date);

    if ($credit->credit_value > $credit->capital_value) {
      //Cuando el cliente paga a tiempo
      if ($payment_date >= $now) {
        // Primer pago
        if ($installment->capital_value >= (float)$installment->paid_capital) {
          $helpPendingCapital = $installment->capital_value - (float)$installment->paid_capital;
          if ((int)$balance > (int) $helpPendingCapital) {
            $capital = $helpPendingCapital;
            $balance = $balance - $capital;
            $helpPendingInterest = $installment->interest_value - ($installment->paid_balance - $installment->paid_capital);

            if ((int) $balance >= (int)  $helpPendingInterest) {
              $interest =  $helpPendingInterest;
              $balance = $balance - $interest;
              $status = 1;
              $step = 3;
            } else {
              $interest = $balance;
              $balance = $balance - $interest;
              $step = 4;
            }
          } else {
            $capital = $balance;
            $balance = $balance - $capital;
            $step = 5;
          }
        }
      }

      //Cuando el cliente paga después de la fecha programada
      if ($payment_date < $now) {
        $days_past_due = $installment->days_past_due ? $installment->days_past_due :  $now->diffInDays($payment_date);
        $day_value_default = $installment->interest_value * $configurations->late_interest_day;
        $late_interests_value = $day_value_default * $days_past_due;
        $installment->days_past_due  = $days_past_due;

        $helpPendingCapital = $installment->capital_value - (float) $installment->paid_capital;

        if (!$late_interests_value) { $status = 1; }

        if ((int)$balance > (int)$helpPendingCapital) {
          $capital = $helpPendingCapital < 0 ? 0 : $helpPendingCapital;
          $balance = $balance - $capital;
          $paidInterest = $installment->paid_balance - $installment->paid_capital;
          $helpPendingInterest = $paidInterest > $installment->interest_value ? 0 : $installment->interest_value - $paidInterest;
          //Hay intereses pendientes
          if ((int)$balance > (int)$helpPendingInterest) {
            $interest =  $helpPendingInterest;
            $balance = $balance - $interest;
            $helpPaidLateInt = $paidInterest > $installment->interest_value ? $paidInterest - $installment->interest_value : 0;
            $helpPendingLateIint =  $late_interests_value - $helpPaidLateInt;

            if ((int) $balance >= (int) $helpPendingLateIint) {
              $late_interest = $helpPendingLateIint;
              $balance = $balance - $late_interest;
              $status = 1;
              $step = 1;
            } else {
              $late_interest = $balance;
              $step = 2;
              $balance = $balance - $late_interest;
            }
          } else {
            $interest = $balance;
            $balance = $balance - $interest;

            $step = 6;
          }
        } else {
          $capital = $balance;
          $balance = $balance - $capital;
          $step = 7;
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
      // $credit_paid->updateValuesCredit(
      //   $request,
      //   $credit->id,
      //   $paidValue,
      //   $capital,
      //   $interest
      // );
      // $entry_id =  $this->saveEntryInstallment($credit, $amount, $capital + $interest + $late_interest, $no_installment, $balance, $user_id, $subject);
    }

    return [
      'installment' => $installment,
      'balance' => $balance,
      'amount' => $amount,
      'capital' => $capital,
      'interest' => $interest,
      'step' => $step,
      'status' => $status,
      'late_interest' => $late_interest ??  null,
      'late_interests_value' => $late_interests_value ?? null,
      'helpPendingLateIint' => $helpPendingLateIint ?? null,
      'helpPendingInterest' => $helpPendingInterest ?? null,
      'helpPendingCapital' => $helpPendingCapital ?? null,
      'paidInterest' => $paidInterest ??  null,
      'no_installment' => $no_installment,
      'entry_id' => $entry_id ?? 'undefined'
    ];
    // return [
    //   'balance' => $balance,
    //   'no_installment' => $no_installment,
    //   'entry_id' => $entry_id,
    //   'installment' => $installment
    // ];
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

    if ($balance_credit > 0) {
      //Cuando el cliente paga a tiempo
      $step = 10;

      // $helpPendingCapital = $installment->paid_capital >= $installment->capital_value ? 0 : $installment->capital_value - $installment->paid_capital;
      // if ($balance >= $helpPendingCapital) {
      //   $step = 1;
      //   $capital = $balance;
      //   $balance = $balance - $capital;
      // } else {
      //   $capital = $helpPendingCapital;
      //   $balance = $balance - $capital;
      // }

      // $installment->paid_balance +=  ($capital + $interest + $late_interest);
      // $installment->paid_capital += $capital;
      // $installment->capital_value += $adittionalBalance;
      // $installment->status  = $status;
      $installment->credit_deposit = $balance;
      $installment->payment_register = date('Y-m-d');


      if (!$installment->save()) {
        return false;
      }
      $credit_paid = new CreditController;
      $credit_paid->updateValuesCredit($request, $credit->id, $amount, $balance, $interest);
      $entry_id =  $this->saveEntryInstallment($credit, $amount, $balance, $no_installment, $balance, $user_id, 'Abono a crédito');
    }

    if ($configurations->method &&  $configurations->method == "GENERAL") {
      $generalMethod->updateInstallmentsFromAbonoCredito($credit->id);
    } else {
      $franchiseMethod->updateInstallmentsFromAbonoCredito($credit->id);
    }

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
      'entry_id' => $entry_id,
      'late_interest' => $late_interest ?? null,
      'late_interests_value' => $late_interests_value ?? null,
      'helpPendingLateIint' => $helpPendingLateIint ?? null,
      'paidInterest' => $paidInterest ?? null,
      'balance_credit' => $balance_credit ?? null
    ];
  }

  public function saveEntryInstallment(Credit $credit, $amount_receipt, $amount_paid, $no_installment, $balance,  $user_id, $quote = null)
  {
    $amount = $amount_paid;
    $amount_receipt = '$' . number_format($amount_receipt, 0, ',', '.');
    $amount_paid = '$' . number_format($amount_paid, 0, ',', '.');
    $balance = '$' . number_format($balance, 0, ',', '.');
    $type_entry = $quote ? $quote : 'Pago de cuota';
    $client = $credit->client()->first();

    try {
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
          . "Cupo crédito: {$client->maximum_credit_allowed}";
        $entry->date = date('Y-m-d');
        $entry->type_entry = $type_entry;
        $entry->price = $amount;
        $entry->save();
        return  $entry->id;
      }
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'code' =>  500,
        'message' => 'No se ha guardado',
      ], 200);
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

  // Reversar pago de cuota individual
  public function reversePaymentInstallment(Request $request, $id)
  {
    $configurations = Company::first();
    $generalMethod = new GeneralMethodController();
    $franchiseMethod = new FranchiseMethodController();

    date_default_timezone_set('America/Bogota');
    $headquarter_id = $request->user()->headquarter_id;
    $user_id = $request->user()->id;

    $installment = Installment::find($request->id);
    $paid_balance = $installment->paid_balance;
    $paid_capital =  $installment->paid_capital;
    $interest =  $installment->paid_balance - $installment->paid_capital;
    $credit = $installment->credit;

    $type_output = 'Reversar pago';
    $description = "Reversar pago \n" .
      "#Credito:  $credit->id \n" .
      "#Cuota Nro:  $installment->installment_number \n" .
      "#Cliente:  {$credit->client->name} {$credit->client->last_name}  \n" .
      "Fecha y hora: " . date('Y-m-d h:i:s A');

    // Certificar egreso
    $expense  = new ExpenseController();
    $expense->addExpense($user_id, $headquarter_id, $description, date('Y-m-d'), $type_output, $paid_balance);

    //Resetar valores de la cuota
    $installment->paid_balance = 0;
    $installment->paid_capital = 0;
    $installment->status = 0;
    $installment->save();

    //Restar valores en el crédito
    $credit_paid = new CreditController;
    $credit_paid->updateValuesCredit($request, $credit->id, $paid_balance * -1,  $paid_capital * -1, $interest * -1, true);

    //Actualizar valores de cuotas
    if ($configurations->method &&  $configurations->method == "GENERAL") {
      $generalMethod->updateInstallments($credit->id);
    } else {
      $franchiseMethod->updateInstallments($credit->id);
    }
  }
}
