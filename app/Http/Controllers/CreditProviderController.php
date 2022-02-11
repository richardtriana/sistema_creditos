<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\CreditProvider;
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
		$credit_provider->last_editor = 1;
		$credit_provider->credit_id = $credit_id;
		$credit_provider->provider_id = $request['provider_id'];
		$credit_provider->headquarter_id = $request['headquarter_id'];
		$credit_provider->last_editor = 1;
		$credit_provider->credit_value = $request['credit_value'];
		$credit_provider->paid_value = 0;
		$credit_provider->pending_value = $request['credit_value'];
		$credit_provider->last_paid = date('Y-m-d');
		$credit_provider->save();
	}

	public function payCreditProvider(CreditProvider $credit_provider, Request $request)
	{
		$credit_provider->paid_value = $credit_provider->paid_value + $request['amount'];
		$credit_provider->pending_value = $credit_provider->pending_value - $request['amount'];
		$credit_provider->save();

		$box = Box::where('headquarter_id', $credit_provider->headquarter_id)->firstOrFail();
		$sub_amount_box = new BoxController();
		$sub_amount_box->subAmountBox($box->id, $request['amount']);
	}
}
