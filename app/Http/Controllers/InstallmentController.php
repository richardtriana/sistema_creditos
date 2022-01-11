<?php

namespace App\Http\Controllers;

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
    $installment->cant_cuota = $request['cant_cuota'];
    $installment->valor = $request['valor'];
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
    $installment->nro_cuota = $request['nro_cuota'];
    $installment->valor = $request['valor'];
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

  public function calcularInstallments(Request $request)
  {
    $capital = $request->valor_credit;
    $interes = $request->interes;
    $cant_cuotas = $request->cant_cuotas;

    $valor = $capital;
    $valor_pago_interes = $interes;
    $cant_cuota = $cant_cuotas;

    $payment_date = [];
    $fechaActual = date('Y-m-d');
    $mes_actual =  (date("d-m-Y", strtotime($fechaActual . "+ 1 months")));

    $listInstallments = [];
    $pagoInteres = [];
    $pagoCapital = [];
    // exit;
    $installment =
      ($valor *
        ((pow(1 + $valor_pago_interes / 100, $cant_cuota) *
          $valor_pago_interes) /
          100)) /
      (pow(1 + $valor_pago_interes / 100, $cant_cuota) - 1);

    for ($i = 0; $i < $cant_cuotas; $i++) {

      $payment_date[$i] = (date("Y-m-d", strtotime($mes_actual . "+ $i months")));

      $pagoInteres[$i] = ($valor * ($valor_pago_interes / 100));
      $pagoCapital[$i] = $installment - $pagoInteres[$i];
      $valor = ($valor - $pagoCapital[$i]);

      foreach ($pagoCapital as $pc) {
        $listInstallments[$i]['pagoCapital'] = (float) number_format($pc, 2, '.', '');
      }
      foreach ($pagoInteres as $key => $pi) {
        $listInstallments[$i]['pagoInteres'] = (float) number_format($pi, 2, '.', '');
      }
      foreach ($payment_date as $fp) {
        $listInstallments[$i]['payment_date'] = (date($fp));
        $listInstallments[$i]['saldo_capital'] = (float) number_format($valor, 2, '.', '');
        $listInstallments[$i]['valor_cuota'] = (float) number_format($installment, 2, '.', '');
      }
      $listInstallments[$i]['cant_cuota'] = $i + 1;
    }

    return ['listInstallments' => $listInstallments, 'installment' => (float) number_format($installment, 2, '.', '')];
  }

  public function payInstallment($id)
  {
    $installment = Installment::findOrFail($id);
    $installment->status = '1';
    $installment->payment_date = date('Y-m-d');
    $installment->save();

    $print_cuota = new PrintTicketController;
    $print_cuota = $print_cuota->printInstallment($id);
  }

  public function printTable(Request $request)
  {
    $credit_id = $request->credit_id;

    $credit = Credit::find($credit_id)->first();
    $client = $credit->client()->get();
    $installments = $credit->installments()->get();

    $details = [
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
