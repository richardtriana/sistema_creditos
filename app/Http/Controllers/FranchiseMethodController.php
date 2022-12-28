<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FranchiseMethodController extends InstallmentController
{

  // Calculo de cuotas de sistema general
  public function calculateInstallments(Request $request)
  {
    $capital = $request->credit_value;
    $interest = $request->interest;
    $number_installments = $request->number_installments;
    $start_date = date('Y-m-d');

    if ($request->start_date && $request->start_date != 'undefined') {
      $start_date = $request->start_date;
    }

    $value = $capital;
    $valor_pago_interes = $interest;
    $installment_number = $number_installments;

    $payment_date = [];
    $fechaInicio = $start_date;
    $mes_actual =  (date("Y-m-d", strtotime($fechaInicio . "+ 1 months")));

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

 

  // Actualizar valor de todas las cuotas de un credito
  public function updateInstallments($credit_id)
  {
    $credit = Credit::findOrFail($credit_id);
    $capital = $credit->credit_value - $credit->capital_value;
    $installments = $credit->installments()
      ->where('status', 0)
      ->get();

    $interest = $credit->interest;
    $number_installments = count($installments);
    $start_date = date('Y-m-d');

    if ($credit->start_date && $credit->start_date != 'undefined') {
      $start_date = $credit->start_date;
    }

    $value = $capital;
    $valor_pago_interes = $interest;
    $installment_number = $number_installments;

    $payment_date = [];
    $fechaInicio = $start_date;
    $mes_actual =  (date("Y-m-d", strtotime($fechaInicio . "+ 1 months")));

    $listInstallments = [];
    $pagoInteres = [];
    $pagoCapital = [];

    if ($number_installments) {
      $installment =
        ($value *
          ((pow(1 + $valor_pago_interes / 100, $installment_number) *
            $valor_pago_interes) /
            100)) /
        (pow(1 + $valor_pago_interes / 100, $installment_number) - 1);

      for ($i = 0; $i < $number_installments; $i++) {
        $id_installment = $installments[$i]->id;
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
