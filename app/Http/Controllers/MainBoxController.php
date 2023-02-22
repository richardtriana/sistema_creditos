<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\MainBox;
use Illuminate\Http\Request;
use App\Models\MainBoxHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MainBoxController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:box.index', ['only' => ['index', 'currentBalance']]);
		$this->middleware('permission:box.update', ['only' => ['update', 'addAmountMainBox', 'subAmountMainBox', 'cashRegister']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$main_box = MainBox::first();
		$last_editor = $main_box->last_editor()->first();

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'main_box' => $main_box,
			'last_editor' => $last_editor
		]);
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
	 * @param  \App\Http\Requests\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function show(MainBox $mainBox)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function edit(MainBox $mainBox)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, MainBox $mainBox)
	{
		$user =  $request->user();
		$validate = Validator::make($request->all(), [
			'amount' => 'required|numeric'
		]);

		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}

		$amount =  $request->amount;

		if ($mainBox->initial_balance <= 0) {
			$mainBox->initial_balance =  $amount;
		}
		$mainBox->current_balance = $mainBox->current_balance + $amount;

		if ($mainBox->current_balance <= 0) {
			$mainBox->input = 0;
			$mainBox->output = 0;
		}
		$mainBox->last_editor = $request->user()->id;
		$mainBox->last_update = date("Y-m-d");
		$mainBox->save();

		$mainBoxHistory = new MainBoxHistoryController();
		$mainBoxHistory->store($request, $mainBox, 'Valor añadido');

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'mainBox' =>  $mainBox
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(MainBox $mainBox)
	{
		//
	}

	public function currentBalance()
	{
		$main_box = MainBox::first();
		$current_balance = $main_box->current_balance;
		return $current_balance;
	}

	public function addAmountMainBox(Request $request, $amount)
	{
		$user =  $request->user();

		$main_box = MainBox::first();
		if ($main_box->initial_balance == 0) {
			$main_box->initial_balance =  $amount;
		}
		$main_box->input = $main_box->input + $amount;
		$main_box->current_balance = $main_box->current_balance + $amount;
		$main_box->save();

		$mainBoxHistory = new MainBoxHistoryController();
		$mainBoxHistory->store($request, $main_box, 'Valor añadido');
	}

	public function subAmountMainBox(Request $request, $amount)
	{
		$user =  $request->user();
		$main_box = MainBox::first();
		$main_box->current_balance = $main_box->current_balance - $amount;
		$main_box->output = $main_box->output + $amount;
		$main_box->save();

		$mainBoxHistory = new MainBoxHistoryController();
		$mainBoxHistory->store($request, $main_box, 'Valor añadido');
	}

	public function cashRegister(Box $box, Request $request)
	{
		//Se recoge caja
		$validate = Validator::make($request->all(), [
			'add_amount' => 'required|numeric',
			'current_balance' => 'required|numeric',
		]);

		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}

		$this->addAmountMainBox($request, $request->current_balance);
		$box->current_balance = 0;
		$box->initial_balance = 0;
		$box->input = 0;
		$box->output = 0;
		$box->cash = $request->cash ?: $box->cash;
		$box->consignment_to_client = $request->consignment_to_client ?: $box->consignment_to_client;
		$box->payment_to_provider = $request->payment_to_provider ?: $box->payment_to_provider;
		$box->status = $request->status ?: $box->status;
		$box->observations = $request->observations ?: $box->observations;
		$box->save();

		$boxHistory = new BoxHistoryController();
		$boxHistory->store($request, $box, 'Arqueo de Caja');

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'box' =>  $box
		], 200);
	}

	public function collectAmount(Box $box, Request $request)
	{
		//Se recolecta solo una cantidad determinada
		$validate = Validator::make($request->all(), [
			'add_amount' => 'required|numeric',
			'current_balance' => 'required|numeric',
		]);

		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}

		$this->addAmountMainBox($request, $request->add_amount);
		$box->current_balance = $box->current_balance - $request->add_amount;
		$box->initial_balance = 0;
		$box->input = 0;
		$box->output = 0;
		$box->cash = $request->cash ?: $box->cash;
		$box->consignment_to_client = $request->consignment_to_client ?: $box->consignment_to_client;
		$box->payment_to_provider = $request->payment_to_provider ?: $box->payment_to_provider;
		$box->status = $request->status ?: $box->status;
		$box->observations = $request->observations ?: $box->observations;
		$box->save();

		$boxHistory = new BoxHistoryController();
		$boxHistory->store($request, $box, 'Arqueo de Caja');

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'box' =>  $box
		], 200);
	}
}
