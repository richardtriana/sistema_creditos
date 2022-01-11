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
			$clients  = 	$clients->where('nro_documento', 'LIKE', "%$request->client%")
				->orWhere('nombres', 'LIKE', "%$request->client%")
				->orWhere('email', 'LIKE', "%$request->client%")
				->orWhere('apellidos', 'LIKE', "%$request->client%");
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
		$client->nombres = $request['nombres'];
		$client->apellidos = $request['apellidos'];
		$client->tipo_documento = $request['tipo_documento'];
		$client->nro_documento = $request['nro_documento'];
		$client->email = $request['email'];
		$client->fecha_nacimiento = $request['fecha_nacimiento'];
		$client->genero = $request['genero'];
		$client->celular1 = $request['celular1'];
		$client->celular2 = $request['celular2'];
		$client->direccion = $request['direccion'];
		$client->estado_civil = $request['estado_civil'];
		$client->lugar_trabajo = $request['lugar_trabajo'];
		$client->cargo = $request['cargo'];
		$client->independiente = $request['independiente'];
		$client->foto = 'undefined';
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
		$client->nombres = $request['nombres'];
		$client->apellidos = $request['apellidos'];
		$client->tipo_documento = $request['tipo_documento'];
		$client->nro_documento = $request['nro_documento'];
		$client->email = $request['email'];
		$client->fecha_nacimiento = $request['fecha_nacimiento'];
		$client->genero = $request['genero'];
		$client->celular1 = $request['celular1'];
		$client->celular2 = $request['celular2'];
		$client->direccion = $request['direccion'];
		$client->estado_civil = $request['estado_civil'];
		$client->lugar_trabajo = $request['lugar_trabajo'];
		$client->cargo = $request['cargo'];
		$client->independiente = $request['independiente'];
		$client->foto = 'undefindef';
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
