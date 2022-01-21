<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$clients = Client::select();
		if ($request->client && ($request->client != '')) {
			$clients  = 	$clients->where('document', 'LIKE', "%$request->client%")
				->orWhere('name', 'LIKE', "%$request->client%")
				->orWhere('email', 'LIKE', "%$request->client%")
				->orWhere('last_name', 'LIKE', "%$request->client%");
		}
		$clients = $clients->paginate(10);

		return $clients;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id)
	{
		//
		$client = Client::findOrFail($id);
		Client::destroy($id);
		return redirect('client')->with('mensaje', 'Client eliminado correctamente');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$client = new Client();
		$client->name = $request['name'];
		$client->last_name = $request['last_name'];
		$client->type_document = $request['type_document'];
		$client->document = $request['document'];
		$client->email = $request['email'];
		$client->birth_date = $request['birth_date'];
		$client->gender = $request['gender'];
		$client->phone_1 = $request['phone_1'];
		$client->phone_2 = $request['phone_2'];
		$client->address = $request['address'];
		$client->civil_status = $request['civil_status'];
		$client->workplace = $request['workplace'];
		$client->occupation = $request['occupation'];
		$client->independent = $request['independent'];
		$client->photo = 'undefined';
		$client->save();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Client  $client
	 * @return \Illuminate\Http\Response
	 */
	public function show(Client $client)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Client  $client
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Client $client)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Client  $client
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Client $client)
	{
		$client = Client::find($request->id);
		$client->name = $request['name'];
		$client->last_name = $request['last_name'];
		$client->type_document = $request['type_document'];
		$client->document = $request['document'];
		$client->email = $request['email'];
		$client->birth_date = $request['birth_date'];
		$client->gender = $request['gender'];
		$client->phone_1 = $request['phone_1'];
		$client->phone_2 = $request['phone_2'];
		$client->address = $request['address'];
		$client->civil_status = $request['civil_status'];
		$client->workplace = $request['workplace'];
		$client->occupation = $request['occupation'];
		$client->independent = $request['independent'];
		$client->photo = 'undefindef';
		$client->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Client  $client
	 * @return \Illuminate\Http\Response
	 */

	public function destroy($id)
	{
		//
	}

	public function changeStatus(Client $client)
	{
		//
		$c = Client::find($client->id);
		// $client->status = '0';
		$c->status = !$c->status;
		$c->save();
	}

	public function credits(Request $request, $id)
	{
		$client = Client::find($id);
		return $client->credits()->get();
	}

	public function filterClientList(Request $request)
	{
		if (!$request->client || $request->client == '') {
			$clients = Client::select()
				->where('status', 1)
				->get();
		} else {
			$clients = Client::select()
				->where('status', 1)
				->where('document', 'LIKE', "%$request->client%")
				->orWhere('name', 'LIKE', "%$request->client%")
				->get(20);
		}

		return $clients;
	}
}
