<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\MainBox;
use Illuminate\Http\Request;

class MainBoxController extends Controller
{
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
	 * @param  \App\Http\Requests\Request  $request
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, MainBox $mainBox)
	{
		$amount =  $request->amount;

		if ($mainBox->initial_balance <= 0) {
			$mainBox->initial_balance =  $amount;
		}
		$mainBox->current_balance = $mainBox->current_balance + $amount;
		if ($mainBox->current_balance <= 0) {
			$mainBox->input = 0;
			$mainBox->output = 0;
		}
		$mainBox->save();
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

	public function addAmountMainBox($amount)
	{
		$main_box = MainBox::first();
		if ($main_box->initial_balance == 0) {
			$main_box->initial_balance =  $amount;
		}
		$main_box->input = $main_box->input + $amount;
		$main_box->current_balance = $main_box->current_balance + $amount;
		$main_box->save();
	}

	public function subAmountMainBox($amount)
	{
		$main_box = MainBox::first();
		$main_box->current_balance = $main_box->current_balance - $amount;
		$main_box->output = $main_box->output + $amount;
		$main_box->save();
	}

	public function cashRegister(Box $box, Request $request)
	{
		$this->addAmountMainBox($request->add_amount);

		$box->current_balance = $box->current_balance - $request->add_amount;
		$box->initial_balance = 0;
		$box->input = 0;
		$box->output = 0;
		$box->save();
	}
}
