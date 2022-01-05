<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Sede;
use App\Models\Credit;
use App\Models\Fee;
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
        $clientes = Cliente::select();
        $sedes = Sede::select();

        if ($request->credit && ($request->credit != '')) {
            $credits  =     $credits->leftjoin('clientes as c', 'c.id', 'credits.cliente_id')
                ->where('nro_documento', 'LIKE', "%$request->credit%")
                ->orWhere('nombres', 'LIKE', "%$request->credit%")
                ->orWhere('email', 'LIKE', "%$request->credit%")
                ->orWhere('apellidos', 'LIKE', "%$request->credit%")
                ->select('credits.*', 'credits.id as id', 'c.nombres', 'c.apellidos', 'c.nro_documento');
        } else {
            $credits  =     $credits->leftjoin('clientes as c', 'c.id', 'credits.cliente_id')
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

        $listFees = new FeeController();
        $listFees = $listFees->calcularFees($request);

        $credit = new Credit();
        $credit->cliente_id = $request['cliente_id'];
        $credit->deudor = $request['deudor'];
        $credit->deudor_id = $request['deudor_id'];
        $credit->sede_id = $request['sede_id'];
        $credit->cant_cuotas = $request['cant_cuotas'];
        $credit->cant_cuotas_pagadas = $request['cant_cuotas_pagadas'];
        $credit->dia_limite = $request['dia_limite'];
        $credit->estado = $request['estado'];
        $credit->fecha_inicio = date('Y-m-d');
        $credit->interes = $request['interes'];
        $credit->porcentaje_interes_anual = $request['porcentaje_interes_anual'];
        $credit->usu_crea = $request['usu_crea'];
        $credit->valor_credit = $request['valor_credit'];
        $credit->valor_abonado = $request['valor_abonado'];
        $credit->valor_capital = $request['valor_capital'];
        $credit->valor_interes = $request['valor_interes'];
        $credit->valor_cuota = $listFees['fee'];
        $credit->save();

        foreach ($listFees['listFees'] as $nueva_cuota) {
            $fee = new Fee();
            $fee->credit_id = $credit->id;
            $fee->nro_cuota = $nueva_cuota['cant_cuota'];
            $fee->valor = $nueva_cuota['valor_cuota'];
            $fee->fecha_pago = $nueva_cuota['fecha_pago'];
            $fee->valor_pago_interes = $nueva_cuota['pagoInteres'];
            $fee->valor_pago_capital = $nueva_cuota['pagoCapital'];
            $fee->save();
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
        $credit->cliente_id = $request['cliente_id'];
        $credit->deudor_id = $request['deudor_id'];
        $credit->deudor = $request['deudor'];
        $credit->sede_id = $request['sede_id'];
        $credit->cant_cuotas = $request['cant_cuotas'];
        $credit->cant_cuotas_pagadas = $request['cant_cuotas_pagadas'];
        $credit->dia_limite = $request['dia_limite'];
        $credit->estado = $request['estado'];
        $credit->fecha_inicio = $request['fecha_inicio'];
        $credit->interes = $request['interes'];
        $credit->porcentaje_interes_anual = $request['porcentaje_interes_anual'];
        $credit->usu_crea = $request['usu_crea'];
        $credit->valor_cuota = $request['valor_cuota'];
        $credit->valor_credit = $request['valor_credit'];
        $credit->valor_abonado = $request['valor_abonado'];
        $credit->valor_capital = $request['valor_capital'];
        $credit->valor_interes = $request['valor_interes'];
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

    public function cambiarEstado(Credit $credit)
    {
        //
        $cre = Credit::find($credit->id);
        $cre->estado = !$cre->estado;
        $cre->save();
    }

    public function fees(Request $request, $id)
    {
        
        $credit = Credit::findOrFail($id);
        return $credit->fees()->get();
    }
}
