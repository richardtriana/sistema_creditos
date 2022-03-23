<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Company;
use App\Models\Expense;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\URL;


class ExpenseController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:expense.index', ['only' => ['index', 'show']]);
		$this->middleware('permission:expense.store', ['only' => ['store']]);
		$this->middleware('permission:expense.update', ['only' => ['update']]);
		$this->middleware('permission:expense.delete', ['only' => ['destroy']]);
		$this->middleware('permission:expense.status', ['only' => ['changeStatus']]);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index(Request $request)
	{
		$expenses = Expense::with('user')->paginate(20);
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
		$expense->headquarter_id = $request->user()->headquarter_id;
		$expense->user_id = $request->user()->id;
		$expense->status = 1;
		$expense->description = $request['description'];
		$expense->date = $request['date'];
		$expense->type_output = $request['type_output'];
		$expense->price = $request['price'];
		$expense->save();

		$box = Box::where('headquarter_id', $expense->headquarter_id)->firstOrFail();
		$sub_amount_box = new BoxController();
		$sub_amount_box->subAmountBox($box->id, $request['price']);
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
		$expense->save();
	}

	public function showExpense(Expense $expense)
	{
		$company = Company::first();
		$headquarter = $expense->headquarter()->first();
		$user = $expense->user()->first();

		$details = [
			'company' => $company,
			'expense' => $expense,
			'headquarter' => $headquarter,
			'user' => $user,
			'url' => URL::to('/')
		];

		$pdf = PDF::loadView('templates.expense_information', $details);
		$pdf = $pdf->download('expense_information.pdf');

		$data = [
			'status' => 200,
			'pdf' => base64_encode($pdf),
			'message' => 'Tabla generada en pdf'
		];

		return response()->json($data);
	}
}
