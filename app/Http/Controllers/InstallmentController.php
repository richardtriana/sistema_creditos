<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Credit;
use App\Models\Entry;
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

 
  public function saveEntryInstallment(Credit $credit, $amount_receipt, $amount_paid, $no_installment, $balance,  $user_id, $quote)
  {
    $amount = $amount_paid;
    $amount_receipt = '$' . number_format($amount_receipt, 0, ',', '.');
    $amount_paid = '$' . number_format($amount_paid, 0, ',', '.');
    $balance = '$' . number_format($balance, 0, ',', '.');
    $type_entry = $quote ? 'Pago de cuota' : 'Abono a crédito';

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
        // . "Efectivo: {$amount_receipt}\n"
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
