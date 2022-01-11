<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sede;
use App\Models\Credit;
use App\Models\Installment;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $credits = Credit::select();
        $clients = Client::select();
        $sedes = Sede::select();

        if ($request->credit && ($request->credit != '')) {
            $credits  =     $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
                ->where('nro_documento', 'LIKE', "%$request->credit%")
                ->orWhere('nombres', 'LIKE', "%$request->credit%")
                ->orWhere('email', 'LIKE', "%$request->credit%")
                ->orWhere('apellidos', 'LIKE', "%$request->credit%")
                ->select('credits.*', 'credits.id as id', 'c.nombres', 'c.apellidos', 'c.nro_documento');
        } else {
            $credits  =     $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
                ->where('nro_documento', 'LIKE', "%$request->credit%")
                ->orWhere('nombres', 'LIKE', "%$request->credit%")
                ->orWhere('email', 'LIKE', "%$request->credit%")
                ->orWhere('apellidos', 'LIKE', "%$request->credit%")
                ->select('credits.*', 'credits.id as id', 'c.nombres', 'c.apellidos', 'c.nro_documento');
        }

        $credits = $credits->paginate(10);

        return $credits;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $credit = Credit::findOrFail($id);
        Credit::destroy($id);
        return redirect('credit')->with('mensaje', 'Credit eliminado correctamente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $listInstallments = new InstallmentController();
        $listInstallments = $listInstallments->calcularInstallments($request);

        $credit = new Credit();
        $credit->client_id = $request['client_id'];
        $credit->deudor = $request['deudor'];
        $credit->deudor_id = $request['deudor_id'];
        $credit->sede_id = $request['sede_id'];
        $credit->cant_cuotas = $request['cant_cuotas'];
        $credit->cant_cuotas_pagadas = $request['cant_cuotas_pagadas'];
        $credit->dia_limite = $request['dia_limite'];
        $credit->status = $request['status'];
        $credit->fecha_inicio = date('Y-m-d');
        $credit->interes = $request['interes'];
        $credit->porcentaje_interes_anual = $request['porcentaje_interes_anual'];
        $credit->usu_crea = $request['usu_crea'];
        $credit->valor_credit = $request['valor_credit'];
        $credit->valor_abonado = $request['valor_abonado'];
        $credit->capital_value = $request['capital_value'];
        $credit->interest_value = $request['interest_value'];
        $credit->valor_cuota = $listInstallments['installment'];
        $credit->save();

        foreach ($listInstallments['listInstallments'] as $nueva_cuota) {
            $installment = new Installment();
            $installment->credit_id = $credit->id;
            $installment->nro_cuota = $nueva_cuota['cant_cuota'];
            $installment->valor = $nueva_cuota['valor_cuota'];
            $installment->payment_date = $nueva_cuota['payment_date'];
            $installment->valor_pago_interes = $nueva_cuota['pagoInteres'];
            $installment->valor_pago_capital = $nueva_cuota['pagoCapital'];
            $installment->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show(Credit $credit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Credit $credit)
    {
        $credit = Credit::find($request->id);
        $credit->client_id = $request['client_id'];
        $credit->deudor_id = $request['deudor_id'];
        $credit->deudor = $request['deudor'];
        $credit->sede_id = $request['sede_id'];
        $credit->cant_cuotas = $request['cant_cuotas'];
        $credit->cant_cuotas_pagadas = $request['cant_cuotas_pagadas'];
        $credit->dia_limite = $request['dia_limite'];
        $credit->status = $request['status'];
        $credit->fecha_inicio = $request['fecha_inicio'];
        $credit->interes = $request['interes'];
        $credit->porcentaje_interes_anual = $request['porcentaje_interes_anual'];
        $credit->usu_crea = $request['usu_crea'];
        $credit->valor_cuota = $request['valor_cuota'];
        $credit->valor_credit = $request['valor_credit'];
        $credit->valor_abonado = $request['valor_abonado'];
        $credit->capital_value = $request['capital_value'];
        $credit->interest_value = $request['interest_value'];
        $credit->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Credit $credit)
    {
        //
        $cre = Credit::find($credit->id);
        $cre->status = !$cre->status;
        $cre->save();
    }

    public function installments(Request $request, $id)
    {

        $credit = Credit::findOrFail($id);
        return $credit->installments()->get();
    }


    public function payMultipleInstallments(Request $request, $id)
    {
        $credit_id = $id;
        $amount = $request['amount'];

        $credit = Credit::find($credit_id)->first();
        $listInstallments = $credit->installments()->where('status', '0')->get();

        for ($i = 0; $i < count($listInstallments) && $amount > 0; $i++) {
            $amount = $amount - $listInstallments[$i]["valor"];
            if ($amount > 0) {
                echo 'first';            //Tambien se debe validar la fecha de pago
                
                if ($amount <= $listInstallments[$i]["valor"]) {
                    $installment = Installment::find($listInstallments[$i]['id'])->first();
                    $installment->paid_balance = $amount - $installment->valor;
                    $installment->save();
                } else {
                    echo 'try again';
                }
            }
        }
    }
}
