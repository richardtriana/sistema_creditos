<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
	public function ReportPortfolio()
	{
		$now = date("Y-m-d");
		$payment_date_add_days = date("Y-m-d", strtotime($now . "+ 5 days"));

		$installments = Installment::select(
			'installments.*',
			'credits.client_id',
			'clients.name',
			'clients.last_name',
			'clients.phone_1',
			'clients.phone_2',
			'credits.credit_value',
			'credits.status',
		)
			->whereDate('payment_date', '<=', $payment_date_add_days)
			->whereNull('payment_register')
			->where('credits.status', 1)
			->leftJoin('credits', 'installments.credit_id', 'credits.id')
			->leftJoin('clients', 'credits.client_id', 'clients.id')
			->paginate(15);

		return $installments;
	}


	public function ReportGeneralCredits(Request $request)
	{
		$credits = Credit::select()->paginate(15);
		$total_credits = new CreditController;
		$total_credits = $total_credits->getTotalValueCredits();

		return ['credits' => $credits, 'total_credits' => $total_credits];
	}

	public function ReportHeadquarters()
	{
		$credits = Credit::select(
			DB::raw('SUM(credit_value) as credit_value '),
			DB::raw('SUM(paid_value) as paid_value'),
			DB::raw('SUM(interest_value) as interest_value'),
			DB::raw('SUM(capital_value) as capital_value'),
			'headquarter_id',
			'headquarters.headquarter as headquarter'
		)
			->selectRaw('count(credits.id) as number_of_credits')
			->selectRaw("count(case when credits.status = '0' then 1 end) as pending")
			->selectRaw("count(case when credits.status = '1' then 1 end) as approved")
			->selectRaw("count(case when credits.status = '2' then 1 end) as rejected")
			->selectRaw("count(case when credits.status = '3' then 1 end) as pending_provider")
			->selectRaw("count(case when credits.status = '4' then 1 end) as completed")
			->groupBy('headquarter_id')
			->groupBy('headquarter')
			->leftJoin('headquarters', 'headquarters.id', 'credits.headquarter_id')
			->paginate(15);

		return $credits;
	}

	public function ReportGeneralClient(Request $request)
	{
		if ($request->document == NULL)
			return false;

		$credits = Credit::select(
			DB::raw('SUM(credit_value) as credit_value '),
			DB::raw('SUM(paid_value) as paid_value'),
			DB::raw('SUM(interest_value) as interest_value'),
			DB::raw('SUM(capital_value) as capital_value'),
			'headquarters.headquarter as headquarter',
			'clients.name',
			'clients.last_name'


		)
			->selectRaw('count(credits.id) as number_of_credits')
			->selectRaw("count(case when credits.status = '0' then 1 end) as pending")
			->selectRaw("count(case when credits.status = '1' then 1 end) as approved")
			->selectRaw("count(case when credits.status = '2' then 1 end) as rejected")
			->selectRaw("count(case when credits.status = '3' then 1 end) as pending_provider")
			->selectRaw("count(case when credits.status = '4' then 1 end) as completed")
			->groupBy('headquarter')
			->groupBy('clients.name', 'clients.last_name')
			->leftJoin('headquarters', 'headquarters.id', 'credits.headquarter_id')
			->leftJoin('clients', 'clients.id', 'credits.client_id')
			->where('clients.document',  'LIKE', "%$request->document%")
			->get();

		return $credits;
	}
}
