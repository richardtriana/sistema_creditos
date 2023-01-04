<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Installment;
use Illuminate\Http\Request;

class GeneralMethodController extends InstallmentController
{

  // Calculo de cuotas de sistema frances
  public function calculateInstallments(Request $request)
  {
    $capital = $request->credit_value;
    $interest = ($request->interest);
    $number_installments = $request->number_installments;
    $general_tax = $number_installments * $interest;
    $total_credit = $capital * $general_tax;
    $valor_pago_interes = $total_credit - $capital;

    $fechaInicio = date('Y-m-d');

    if ($request->start_date && $request->start_date != 'undefined') {
      $fechaInicio = $request->start_date;
    }

    $value = $capital;

    $payment_date = [];
    $mes_actual =  (date("Y-m-d", strtotime($fechaInicio . "+ 1 months")));

    $listInstallments = [];
    $pagoInteres = [];
    $pagoCapital = [];

    $installment = $capital * $interest;

    for ($i = 0; $i < $number_installments; $i++) {

      $payment_date[$i] = (date("Y-m-d", strtotime($mes_actual . "+ $i months")));

      $pagoInteres[$i] = $valor_pago_interes / $number_installments;
      $pagoCapital[$i] = $capital / $number_installments;
      $value = ($value - $pagoCapital[$i]);

      foreach ($pagoCapital as $pc) {
        $listInstallments[$i]['pagoCapital'] = (float) number_format($pc, 2, '.', '');
      }
      foreach ($pagoInteres as $pi) {
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


  // Actualizar valor de todas las cuotas de un credito
  public function updateInstallments($credit_id)
  {
    $credit = Credit::findOrFail($credit_id);

    $paidTotalCredit =  $credit->installments()->selectRaw("
    SUM(interest_value) AS interest_value,
      SUM(paid_capital) AS paid_capital,
      SUM(paid_balance) AS paid_balance
    ")->where('paid_balance', '>', 0)->first();

    $capital = $credit->credit_value - $paidTotalCredit->paid_capital;
    
    $installments = $credit->installments()
      ->where('status', 0)
      ->get();

    $interest = ($credit->interest);
    $number_installments = count($installments);
    $start_date = date('Y-m-d');

    if ($credit->start_date && $credit->start_date != 'undefined') {
      $start_date = $credit->start_date;
    }

    $value = $capital;
    $general_tax = $number_installments * $interest;
    $total_credit = $capital * $general_tax;
    $valor_pago_interes = $total_credit - $capital;

    $payment_date = [];
    $fechaInicio = $start_date;
    $mes_actual =  (date("Y-m-d", strtotime($fechaInicio . "+ 1 months")));

    $listInstallments = [];
    $pagoInteres = [];
    $pagoCapital = [];

    if ($number_installments) {
      $installment = $capital * $interest;

      for ($i = 0; $i < $number_installments; $i++) {
        $id_installment = $installments[$i]->id;
        $payment_date[$i] = (date("Y-m-d", strtotime($mes_actual . "+ $i months")));

        $pagoInteres[$i] = $valor_pago_interes / $number_installments;
        $pagoCapital[$i] = $capital / $number_installments;
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
        Installment::findOrFail($id_installment)->update(
          [
            'value' =>  $listInstallments[$i]['installment_value'],
            'interest_value' =>  $listInstallments[$i]['pagoInteres'],
            'capital_value' =>  $listInstallments[$i]['pagoCapital'],
            'capital_balance' => $listInstallments[$i]['saldo_capital']
          ]
        );
        $listInstallments[$i]['installment_number'] = $i + 1;
      }
      $credit->update(
        ['installment_value' =>  $listInstallments[0]['installment_value']]
      );
    }
  }
}
