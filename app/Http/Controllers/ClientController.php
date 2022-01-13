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
			$clients  = 	$clients->where('document_number', 'LIKE', "%$request->client%")
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
		$client->document_number = $request['document_number'];
		$client->email = $request['email'];
		$client->fecha_nacimiento = $request['fecha_nacimiento'];
		$client->genero = $request['genero'];
		$client->cell_phone1 = $request['cell_phone1'];
		$client->cell_phone2 = $request['cell_phone2'];
		$client->address = $request['address'];
		$client->estado_civil = $request['estado_civil'];
		$client->lugar_trabajo = $request['lugar_trabajo'];
		$client->cargo = $request['cargo'];
		$client->independiente = $request['independiente'];
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
		$client->document_number = $request['document_number'];
		$client->email = $request['email'];
		$client->fecha_nacimiento = $request['fecha_nacimiento'];
		$client->genero = $request['genero'];
		$client->cell_phone1 = $request['cell_phone1'];
		$client->cell_phone2 = $request['cell_phone2'];
		$client->address = $request['address'];
		$client->estado_civil = $request['estado_civil'];
		$client->lugar_trabajo = $request['lugar_trabajo'];
		$client->cargo = $request['cargo'];
		$client->independiente = $request['independiente'];
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
		$c->activo = !$c->activo;
		$c->save();
	}

	public function credits(Request $request, $id)
	{
		$client = Client::find($id);
		return $client->credits()->get();
	}
}
