<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Entry;
use App\Models\Company;
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
		$entries = Entry::select()->paginate(20);
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
		$entry->save();
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
