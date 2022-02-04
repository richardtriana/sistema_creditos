<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Credit;
use App\Models\Installment;
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

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Installment  $installment
   * @return \Illuminate\Http\Response
   */
  public function destroy(Installment $installment)
  {
    //
  }

  public function calculateInstallments(Request $request)
  {
    $capital = $request->credit_value;
    $interest = $request->interest;
    $number_installments = $request->number_installments;
    $start_date = $request->start_date;

    $value = $capital;
    $valor_pago_interes = $interest;
    $installment_number = $number_installments;

    $payment_date = [];
    $fechaInicio = $start_date;
    $mes_actual =  (date("d-m-Y", strtotime($fechaInicio . "+ 1 months")));

    $listInstallments = [];
    $pagoInteres = [];
    $pagoCapital = [];

    $installment =
      ($value *
        ((pow(1 + $valor_pago_interes / 100, $installment_number) *
          $valor_pago_interes) /
          100)) /
      (pow(1 + $valor_pago_interes / 100, $installment_number) - 1);

    for ($i = 0; $i < $number_installments; $i++) {

      $payment_date[$i] = (date("Y-m-d", strtotime($mes_actual . "+ $i months")));

      $pagoInteres[$i] = ($value * ($valor_pago_interes / 100));
      $pagoCapital[$i] = $installment - $pagoInteres[$i];
      $value = ($value - $pagoCapital[$i]);

      foreach ($pagoCapital as $pc) {
        $listInstallments[$i]['pagoCapital'] = (float) number_format($pc, 2, '.', '');
      }
      foreach ($pagoInteres as $key => $pi) {
        $listInstallments[$i]['pagoInteres'] = (float) number_format($pi, 2, '.', '');
      }
      foreach ($payment_date as $fp) {
        $listInstallments[$i]['payment_date'] = (date($fp));
        $listInstallments[$i]['saldo_capital'] = (float) number_format($value, 2, '.', '');
        $listInstallments[$i]['installment_value'] = (float) number_format($installment, 2, '.', '');
      }
      $listInstallments[$i]['installment_number'] = $i + 1;
    }

    return ['listInstallments' => $listInstallments, 'installment' => (float) number_format($installment, 2, '.', '')];
  }

  public function payInstallment($id, $amount = null)
  {
    $installment = Installment::findOrFail($id);
    $credit_id = $installment->credit->id;

    $now = date("Y-m-d");
    $payment_date = $installment->payment_date;
    $payment_date_sub_month = date("Y-m-d", strtotime($payment_date . "- 1 month"));
    $payment_date_add_days = date("Y-m-d", strtotime($payment_date_sub_month . "+ 5 days"));

    $credit_paid = new CreditController;

    // Se verifica si la cuota se paga a tiempo
    if ($payment_date >= $now) {
      if (($now < $payment_date_add_days)) {

        // Solo se cobra capital
        $amount_to_pay = $installment->capital_value;
        if ($amount >= $amount_to_pay) {
          $installment->paid_balance = $amount_to_pay;
          $balance =  $amount - $amount_to_pay;
          $credit_paid = $credit_paid->updateValuesCredit($credit_id, $amount, $amount, 0);
        } else {
          $installment->paid_balance = $amount;
          $balance = 0;
          $credit_paid = $credit_paid->updateValuesCredit($credit_id, $amount, $amount, 0);
        }
      } else {

        // Se cobra interes y capital
        $amount_to_pay = $installment->value;
        $interest = $amount_to_pay - $installment->capital_value;

        if ($amount >= $amount_to_pay) {
          $installment->paid_balance = $amount_to_pay;
          $balance = $amount -  $amount_to_pay;
        } else {
          $installment->paid_balance = $amount;
          $balance = 0;
          $credit_paid = $credit_paid->updateValuesCredit($credit_id, $amount_to_pay, $interest, 0);
        }
      }
    }

    if ($payment_date < $now) {
      $amount_to_pay = $installment->value;
      $interest = $amount_to_pay - $installment->capital_value;

      if ($amount >= $amount_to_pay) {
        $installment->paid_balance = $amount_to_pay;
        $balance = $amount - $amount_to_pay;
        $credit_paid = $credit_paid->updateValuesCredit($credit_id, $amount_to_pay, $interest, 0);
      } else {
        $installment->paid_balance = $amount;
        $balance = 0;
        $credit_paid = $credit_paid->updateValuesCredit($credit_id, $amount_to_pay, $interest, 0);
      }
    }

    $installment->status  = 1;
    $installment->payment_date = date('Y-m-d');
    $installment->save();

    $no_installment = $installment->installment_number;

    return ['balance' => $balance, 'no_installment' => $no_installment];
  }

  public function printTable(Request $request)
  {
    $company = Company::first();

    $credit_id = $request->credit_id;
    $credit = Credit::find($credit_id)->first();
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
}
