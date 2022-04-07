<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\MainBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoxController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:box.index', ['only' => ['index','show']]);
		$this->middleware('permission:box.update', ['only' => ['update', 'addAmountBox','subAmountBox']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$boxes = Box::select()->get();
		return response()->json([
			'status' => 'success',
			'code' => 200,
			'boxes' => $boxes
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
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function show(Box $box)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Box $box)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\Request  $request
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Box $box)
	{
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

		if ($box->initial_balance == 0) {
			$box->initial_balance =  $amount;
		}
		$box->current_balance = $box->current_balance + $amount;
		$box->save();

		$update_main_box = new MainBoxController();
		$update_main_box->subAmountMainBox($amount);

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'box' =>  $box
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Box $box)
	{
		//
	}

	public function addAmountBox($id, $amount)
	{
		$box_id = $id;
		$box = Box::findOrFail($box_id);
		$box->current_balance = $box->current_balance + $amount;
		$box->input = $box->input + $amount;
		$box->save();
	}

	public function subAmountBox($id, $amount)
	{
		$box_id = $id;
		$box = Box::findOrFail($box_id);
		$box->current_balance = $box->current_balance - $amount;
		$box->output = $box->output + $amount;
		$box->save();
	}
}
