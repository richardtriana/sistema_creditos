<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\CreditProvider;
use App\Models\MainBox;
use Illuminate\Http\Request;

class CreditProviderController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$credit_providers = CreditProvider::select();
		$credit_providers = $credit_providers->paginate(10);
		return $credit_providers;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $credit_id)
	{
		$credit_provider = new CreditProvider();
		$data = ([
			'Asesor' => 'Richard Peña',
			'Fecha' => date('Y-m-d'),
			'Monto' => $request['credit_value']
		]);
		if ($credit_provider->history != null) {
			$history = (array) json_decode($credit_provider->history);
		} else {
			$history = array();
		}
		array_push($history, $data);

		$credit_provider->last_editor = 1;
		$credit_provider->credit_id = $credit_id;
		$credit_provider->provider_id = $request['provider_id'];
		$credit_provider->headquarter_id = $request['headquarter_id'];
		$credit_provider->last_editor = 1;
		$credit_provider->credit_value = $request['credit_value'];
		$credit_provider->paid_value = 0;
		$credit_provider->pending_value = $request['credit_value'];
		$credit_provider->last_paid = date('Y-m-d');
		$credit_provider->history = json_encode($history);
		$credit_provider->save();
	}

	public function payCreditProvider(CreditProvider $credit_provider, Request $request)
	{

		$data = ([
			'Asesor' => 'Richard Peña',
			'Fecha' => date('Y-m-d'),
			'Monto' => $request['amount']
		]);
		if ($credit_provider->history != null) {
			$history = (array) json_decode($credit_provider->history);
		} else {
			$history = array();
		}
		array_push($history, $data);
		
		$credit_provider->paid_value = $credit_provider->paid_value + $request['amount'];
		$credit_provider->pending_value = $credit_provider->pending_value - $request['amount'];
		$credit_provider->history = json_encode($history);

		$credit_provider->save();

		$main_box = MainBox::first();
		$main_box->current_balance = $main_box->current_balance - $credit_provider->paid_value;
		$main_box->save();

		if ($credit_provider->pending_value <= 0) {
			$credit = $credit_provider->credit()->first();
			$credit->status = 1;
			$credit->save();
		}
	}
}
