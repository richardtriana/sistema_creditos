<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
	public function index()
	{
		return Pago::paginate(15);
	}

	public function create($id)
	{
		//
		$pago = Pago::findOrFail($id);
		Pago::destroy($id);
		return redirect('pago')->with('mensaje', 'Pago eliminado correctamente');
	}

	public function store(Request $request)
	{

		$pago = new Pago();
		$pago->tipo_deuda = $request['tipo_deuda'];
		$pago->id_deuda = $request['id_deuda'];
		$pago->valor_pago = $request['valor_pago'];
		$pago->nro_cuota = $request['nro_cuota'];
		$pago->interest_value = $request['interest_value'];
		$pago->capital_value = $request['capital_value'];
		$pago->id_tercero = $request['id_tercero'];
    $pago->payment_date = $request['payment_date'];
		$pago->save();
	}

	public function show(Pago $pago)
	{
		//
	}

	public function edit(Pago $pago)
	{
		//
	}

	public function update(Request $request, Pago $pago)
	{
		$pago = Pago::find($request->id);
		$pago->tipo_deuda = $request['tipo_deuda'];
		$pago->id_deuda = $request['id_deuda'];
		$pago->valor_pago = $request['valor_pago'];
		$pago->nro_cuota = $request['nro_cuota'];
		$pago->interest_value = $request['interest_value'];
		$pago->capital_value = $request['capital_value'];
		$pago->id_tercero = $request['id_tercero'];
    $pago->payment_date  = $request['payment_date '];
		$pago->save();
	}

	public function destroy($id)
	{
		//
	}
}
