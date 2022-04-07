<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Entry;
use App\Models\Company;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class EntryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$client = $request->client;
		$document = $request->document;
		$from = $request->from;
		$to = $request->to;
		$this_month = Carbon::now()->month;
		$type_input = $request->type_input;
		$description = $request->description;

		$entries = Entry::select()
			->orderBy('id', 'desc')
			->with(['user:id,name,last_name']);

		if ($client != null || $document != null) {
			$entries = $entries->select('entries.*', 'clients.name', 'clients.last_name', 'clients.document')
				->leftJoin('credits', 'entries.credit_id', 'credits.id')
				->leftJoin('clients', 'credits.client_id', 'clients.id');

			if ($client != null) {
				$entries = $entries->where('clients.name', 'LIKE', "%$client%")
					->orWhere('clients.last_name', 'LIKE', "%$client%");
			}
			if ($document != null) {
				$entries = $entries->where('clients.document', 'LIKE', "$document%");
			}
		};

		$entries = $entries
			->where(function ($query) use ($this_month, $from, $to) {

				$query->whereMonth('date', '<=', $this_month);

				if ($from != '' && $from != 'undefined' && $from != null) {
					$query->whereDate('date', '>=', $from);
				}
				if ($to != '' && $to != 'undefined' && $to != null) {
					$query->whereDate('date', '<=', $to);
				}
			});
		if ($type_input != null) {
			$entries =	$entries->where('type_entry', 'LIKE', "%$type_input%");
		}
		if ($description != null) {
			$entries =	$entries->where('description', 'LIKE', "%$description%");
		}
		$entries = $entries->paginate(15);
		return $entries;
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$credit = Credit::findOrFail($request->data['credit_id']);
		$client = $credit->client()->first();

		$entry =  new Entry();
		$entry->headquarter_id = $credit->headquarter_id;
		$entry->user_id = $request->user()->id;
		$entry->credit_id = $credit->id;
		$entry->description = "Cliente: {$client->name} {$client->last_name}";
		$entry->date = date('Y-m-d');
		$entry->type_entry = 'Pago de cuota';
		$entry->price = $request->data['value'] - $request['value'];
		//$entry->save();
	}

	public function showEntry(Entry $entry)
	{
		$company = Company::first();

		$headquarter = $entry->headquarter()->first();
		$credit = $entry->credit()->first();
		$client = $credit->client()->first();

		$details = [
			'company' => $company,
			'credit' => $credit,
			'client' => $client,
			'entry' => $entry,
			'headquarter' => $headquarter,
			'url' => URL::to('/')
		];

		$pdf = PDF::loadView('templates.entry_information', $details);
		$pdf = $pdf->download('entry_information.pdf');

		$data = [
			'status' => 200,
			'pdf' => base64_encode($pdf),
			'message' => 'Tabla generada en pdf'
		];

		return response()->json($data);
	}
}
