<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index(Request $request)
	{
		$expenses = Expense::select()->paginate(20);
		return $expenses;
	}


	public function changeStatus(Expense $expense)
	{
		$ex = Expense::find($expense->id);
		$ex->status = !$ex->status;
		$ex->save();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$expense =  new Expense();
		$expense->headquarter_id = 1;
		$expense->user_id = 1;
		$expense->status = 1;
		$expense->description = $request['description'];
		$expense->date = $request['date'];
		$expense->type_output = $request['type_output'];
		$expense->price = $request['price'];
		$expense->save();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Client  $client
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Expense $expense)
	{
		$expense->description = $request['description'];
		$expense->date = $request['date'];
		$expense->type_output = $request['type_output'];
		$expense->price = $request['price'];
		$expense->save();
	}
}
