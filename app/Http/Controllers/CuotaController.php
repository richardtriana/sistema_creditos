<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $creditos = Fee::select();
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
        $fee = new Fee();
        $fee->credito_id = $request['credito_id'];
        $fee->cant_cuota = $request['cant_cuota'];
        $fee->valor = $request['valor'];
        $fee->fecha_pago = $request['fecha_pago'];
        $fee->dias_mora = $request['dias_mora'];
        $fee->valor_interes_mora = $request['valor_interes_mora'];
        $fee->valor_pago_interes = $request['valor_pago_interes'];
        $fee->valor_pago_capital = $request['valor_pago_capital'];
        $fee->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function show(Fee $fee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function edit(Fee $fee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        //
        $fee = new Fee();
        $fee->nro_cuota = $request['nro_cuota'];
        $fee->valor = $request['valor'];
        $fee->fecha_pago = $request['fecha_pago'];
        $fee->dias_mora = $request['dias_mora'];
        $fee->valor_interes_mora = $request['valor_interes_mora'];
        $fee->valor_pago_interes = $request['valor_pago_interes'];
        $fee->valor_pago_capital = $request['valor_pago_capital'];
        $fee->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        //
    }

    public function calcularCuotas(Request $request)
    {
        $capital = $request->valor_credito;
        $interes = $request->interes;
        $cant_cuotas = $request->cant_cuotas;

        $valor = $capital;
        $valor_pago_interes = $interes;
        $cant_cuota = $cant_cuotas;


        // var_dump($cant_cuotas);

        $fecha_pago = [];
        $fechaActual = date('Y-m-d');
        $mes_actual =  (date("d-m-Y", strtotime($fechaActual . "+ 1 months")));

        $listCuotas = [];
        $pagoInteres = [];
        $pagoCapital = [];
        // exit;
        $fee =
            ($valor *
                ((pow(1 + $valor_pago_interes / 100, $cant_cuota) *
                    $valor_pago_interes) /
                    100)) /
            (pow(1 + $valor_pago_interes / 100, $cant_cuota) - 1);

        for ($i = 0; $i < $cant_cuotas; $i++) {

            $fecha_pago[$i] = (date("Y-m-d", strtotime($mes_actual . "+ $i months")));

            $pagoInteres[$i] = ($valor * ($valor_pago_interes / 100));
            $pagoCapital[$i] = $fee - $pagoInteres[$i];
            $valor = ($valor - $pagoCapital[$i]);

            foreach ($pagoCapital as $pc) {
                $listCuotas[$i]['pagoCapital'] = (float) number_format($pc, 2, '.', '');
            }
            foreach ($pagoInteres as $key => $pi) {
                $listCuotas[$i]['pagoInteres'] = (float) number_format($pi, 2, '.', '');
            }
            foreach ($fecha_pago as $fp) {
                $listCuotas[$i]['fecha_pago'] = (date($fp));
                $listCuotas[$i]['saldo_capital'] = (float) number_format($valor, 2, '.', '');
                $listCuotas[$i]['valor_cuota'] = (float) number_format($fee, 2, '.', '');
            }
            $listCuotas[$i]['cant_cuota'] = $i + 1;
        }

        return ['listCuotas' => $listCuotas, 'fee' => (float) number_format($fee, 2, '.', '')];
    }

    public function pagarCuota($id)
    {
        $fee = Fee::findOrFail($id);
        $fee->estado = '1';
        $fee->fecha_pago = date('Y-m-d');
        $fee->save();

        $imprimir_cuota = new ImprimirTicketController;
        $imprimir_cuota = $imprimir_cuota->imprimirCuota($id);
    }
}
