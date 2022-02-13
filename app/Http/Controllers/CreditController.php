<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Credit;
use App\Models\Installment;
use App\Models\MainBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

		if ($request->credit && ($request->credit != '')) {
			$credits  =   $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
				->where('document', 'LIKE', "%$request->credit%")
				->orWhere('name', 'LIKE', "%$request->credit%")
				->orWhere('email', 'LIKE', "%$request->credit%")
				->orWhere('last_name', 'LIKE', "%$request->credit%")
				->select('credits.*', 'credits.id as id', 'c.name', 'c.last_name', 'c.document');
		} else {
			$credits  =     $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
				->where('document', 'LIKE', "%$request->credit%")
				->orWhere('name', 'LIKE', "%$request->credit%")
				->orWhere('email', 'LIKE', "%$request->credit%")
				->orWhere('last_name', 'LIKE', "%$request->credit%")
				->select('credits.*', 'credits.id as id', 'c.name', 'c.last_name', 'c.document');
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
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$listInstallments = new InstallmentController();
		$listInstallments = $listInstallments->calculateInstallments($request);

		$user_id = Auth::user();

		$credit = new Credit();
		$credit->client_id = $request['client_id'];
		$credit->provider_id = $request['provider_id'];
		$credit->debtor_id = $request['debtor_id'];
		$credit->user_id = 1;
		$credit->debtor = $request['debtor'];
		$credit->provider = $request['provider'];
		$credit->headquarter_id = $request['headquarter_id'];
		$credit->number_installments = $request['number_installments'];
		$credit->number_paid_installments = $request['number_paid_installments'];
		$credit->day_limit = $request['day_limit'];
		$credit->status = 1;
		$credit->start_date = $request['start_date'];
		$credit->interest = $request['interest'];
		$credit->annual_interest_percentage = $request['annual_interest_percentage'];
		$credit->credit_value = $request['credit_value'];
		$credit->paid_value = $request['paid_value'];
		$credit->capital_value = $request['capital_value'];
		$credit->interest_value = $request['interest_value'];
		$credit->description = $request['description'];
		$credit->disbursement_date = date('Y-m-d');
		$credit->installment_value = $listInstallments['installment'];
		if ($credit->save()) {
			foreach ($listInstallments['listInstallments'] as $new_installment) {
				$installment = new Installment();
				$installment->credit_id = $credit->id;
				$installment->installment_number = $new_installment['installment_number'];
				$installment->value = $new_installment['installment_value'];
				$installment->payment_date = $new_installment['payment_date'];
				$installment->interest_value = $new_installment['pagoInteres'];
				$installment->capital_value = $new_installment['pagoCapital'];
				$installment->capital_balance = $new_installment['saldo_capital'];
				$installment->save();
			}
			if ($request['provider_id']) {
				$credit_provider = new CreditProviderController();
				$credit_provider->store($request, $credit->id);
			}
			$main_box = MainBox::first();
			$main_box->current_balance = $main_box->current_balance - $credit->credit_value;
			$main_box->save();
		}
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
		$credit->client_id = $request['client_id'];
		$credit->provider_id = $request['provider_id'];
		$credit->debtor_id = $request['debtor_id'];
		$credit->debtor = $request['debtor'];
		$credit->provider = $request['provider'];
		$credit->headquarter_id = $request['headquarter_id'];
		$credit->number_installments = $request['number_installments'];
		$credit->number_paid_installments = $request['number_paid_installments'];
		$credit->day_limit = $request['day_limit'];
		$credit->start_date = $request['start_date'];
		$credit->interest = $request['interest'];
		$credit->annual_interest_percentage = $request['annual_interest_percentage'];
		$credit->user_id = $request['user_id'];
		$credit->installment_value = $request['installment_value'];
		$credit->description = $request['description'];
		$credit->disbursement_date = date('Y-m-d');
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
		$credit = Credit::findOrFail($id);
		$credit->destroy($id);
		return redirect('credit')->with('mensaje', 'Credit eliminado correctamente');
	}

	public function changeStatus(Credit $credit)
	{
		//
		$cre = Credit::find($credit->id);
		$cre->status = !$cre->status;
		$cre->save();
	}

	public function installments(Request $request, $id)
	{

		$credit = Credit::findOrFail($id);
		return $credit->installments()->get();
	}

	public function payMultipleInstallments(Request $request, $id)
	{
		$credit_id = $id;
		$amount = $request['amount'];
		$amount_paid = $request['amount'];

		$credit = Credit::findOrFail($credit_id);
		$listInstallments = $credit->installments()->where('status', '0')->get();

		for ($i = 0; $i < count($listInstallments) && $amount > 0; $i++) {
			$pay_installment = new InstallmentController();
			if ($amount > 0) {
				$installment_paid =	$pay_installment->payInstallment($listInstallments[$i]['id'], $amount, $request);
				$balance = $installment_paid['balance'];
			}
			$amount = $balance;
			$no_installment_paid = $installment_paid['no_installment'];
		}
		$print_cuota = new PrintTicketController;
		$print_cuota = $print_cuota->printPayment($id, $amount_paid, $no_installment_paid);
	}

	public function updateValuesCredit($id, $total_amount, $capital, $interest)
	{
		$credit_id = $id;
		$credit = Credit::findOrFail($credit_id);
		$headquarter_id = $credit->headquarter->id;

		$box = Box::where('headquarter_id', $headquarter_id)->firstOrFail();

		$add_amount_box = new BoxController();
		$add_amount_box->addAmountBox($box->id, $total_amount);

		$credit->paid_value = $credit->paid_value + $total_amount;
		$credit->capital_value = $credit->capital_value + $capital;
		$credit->interest_value = $credit->interest_value + $interest;
		$credit->save();
	}
}
