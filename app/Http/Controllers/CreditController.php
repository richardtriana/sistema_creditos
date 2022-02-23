<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Credit;
use App\Models\CreditProvider;
use App\Models\Entry;
use App\Models\Installment;
use App\Models\MainBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
		$status = $request->status != null ? $request->status : 1;
		$status = $status == 0 ? [0, 3] : [$request->status];

		if ($request->credit && ($request->credit != '')) {
			$credits  =   $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
				->select('credits.*', 'credits.id as id', 'c.name', 'c.last_name', 'c.document')
				->where('document', 'LIKE', "%$request->credit%")
				->orWhere('name', 'LIKE', "%$request->credit%")
				->orWhere('email', 'LIKE', "%$request->credit%")
				->orWhere('last_name', 'LIKE', "%$request->credit%")
				->whereIn('credits.status', $status);
		} else {
			$credits  =     $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
				->select('credits.*', 'credits.id as id', 'c.name', 'c.last_name', 'c.document')
				->whereIn('credits.status', $status);
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
		if ($request['provider'] != null && $request['provider'] != 0) {
			$credit->status = 3;
		} else {
			$credit->status = 0;
		}
		$credit->headquarter_id = $request['headquarter_id'];
		$credit->number_installments = $request['number_installments'];
		$credit->number_paid_installments = $request['number_paid_installments'];
		$credit->day_limit = $request['day_limit'];
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
				if (!$installment->save()) {
					Credit::findOrFail($credit->id)->delete();
					return false;
				}
			}
			if ($request['provider_id'] != NULL && $request['provider_id'] != 0) {
				$credit_provider = new CreditProviderController();
				$credit_provider->store($request, $credit->id);
			}
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
		return redirect('credit')->with('mensaje', 'Credito eliminado correctamente');
	}

	public function changeStatus(Request $request,  Credit $credit)
	{
		$credit_provider = '';
		if ($credit->provider_id != null && $credit->provider_id != 0) {
			$credit_provider = CreditProvider::where('credit_id', $credit->id)->first();

			if ($credit_provider != null) {
				if ($credit_provider->pending_value <= 0) {
					if ($request->status  == 1) {
						$update_main_box = new MainBoxController();
						$update_main_box->subAmountMainBox($credit->credit_value);
					}

					if ($request->status  == 2 && $credit->status == 1) {
						$update_main_box = new MainBoxController();
						$update_main_box->addAmountMainBox($credit->credit_value);
					}
				}
				$credit->status = $request->status;
				$credit_provider->status = $request->status;
				$credit_provider->save();
			}
		}

		if ($credit->provider_id == null || $credit->provider_id == 0) {
			if ($request->status  == 1) {
				$update_main_box = new MainBoxController();
				$update_main_box->subAmountMainBox($credit->credit_value);
			}
			if ($request->status  == 2 && $credit->status == 1) {
				$update_main_box = new MainBoxController();
				$update_main_box->addAmountMainBox($credit->credit_value);
			}
			$credit->status = $request->status;
		}
		$credit->save();
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
		$client = $credit->client()->first();
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

		$entry =  new Entry();
		$entry->headquarter_id = $credit->headquarter_id;
		$entry->user_id = 1;
		$entry->credit_id = $credit->id;
		$entry->description = "Cliente: {$client->name} {$client->last_name}";
		$entry->date = date('Y-m-d');
		$entry->type_entry = 'Abono a crédito';
		$entry->price = $amount;
		$entry->save();
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

	public function generalInformation(Credit $credit)
	{
		$credit = Credit::findOrFail($credit->id);
		$data = array();

		if ($credit->provider_id != null && $credit->provider_id != 0) {
			$provider = $credit->provider()->firstOrFail();
			$data['provider'] = $provider;
		}
		if ($credit->debtor_id != null && $credit->debtor_id != 0) {
			$debtor = $credit->debtor()->firstOrFail();
			$data['debtor'] = $debtor;
		}
		return $data;
	}

	public function getTotalValueCredits()
	{
		$credits = Credit::select(
			DB::raw('SUM(credit_value) as credit_value '),
			DB::raw('SUM(paid_value) as paid_value'),
			DB::raw('SUM(interest_value) as interest_value'),
			DB::raw('SUM(capital_value) as capital_value')
		)->first();
		return $credits;
	}
}
