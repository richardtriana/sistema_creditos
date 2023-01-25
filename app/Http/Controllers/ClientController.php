<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:client.index', ['only' => ['index','show','filterClientList', 'credits']]);
		$this->middleware('permission:client.store', ['only' => ['store']]);
		$this->middleware('permission:client.update', ['only' => ['update']]);
		$this->middleware('permission:client.delete', ['only' => ['create']]);
		$this->middleware('permission:client.status', ['only' => ['changeStatus']]);
	}
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

		$validate = Validator::make($request->all(), [
			'headquarter_id' => 'required|integer|exists:headquarters,id',
			'name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'type_document' => 'required|in:CC,CE,NIT,PP,TI',
			'document' => 'required|numeric|min:999999|max:999999999999|unique:clients',
			'profession' => 'nullable|string|max:255',
			'phone_1' => 'nullable|string',
			'phone_2' => 'nullable|string',
			'address' => 'nullable|string',
			'email' => 'nullable|string|email:rfc,dns|unique:clients',
			'birth_date' => 'nullable|date',
			'gender' => 'nullable|string|in:M,F,O',
			'civil_status' => 'nullable|string|in:Soltero,Casado,Union libre,Divorciado,Viudo',
			'workplace' => 'nullable|string|max:255',
			'occupation' => 'nullable|string|max:255',
			'independent' => 'nullable|boolean',
			'maximum_credit_allowed' => 'required|numeric',
		]);

		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}


		$client = new Client();
		$client->headquarter_id = $request['headquarter_id'];
		$client->name = $request['name'];
		$client->last_name = $request['last_name'];
		$client->type_document = $request['type_document'];
		$client->document = $request['document'];
		$client->profession = $request['profession'];
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
		$client->maximum_credit_allowed = $request['maximum_credit_allowed'];
		$client->photo = 'undefined';
		$client->save();


		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'errors' =>  $validate->errors()
		], 200);
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
		$validate = Validator::make($request->all(), [
			'headquarter_id' => 'required|integer|exists:headquarters,id',
			'name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'type_document' => 'required|in:CC,CE,NIT,PP,TI',
			'document' => [
				'required',
				'numeric',
				'between:9999,999999999999',
				Rule::unique('clients')->ignore($client->id)
			],
			'profession' => 'nullable|string|max:255',
			'phone_1' => 'nullable|string',
			'phone_2' => 'nullable|string',
			'address' => 'nullable|string',
			'email' => [
				'nullable',
				'string',
				'email:rfc,dns',
				Rule::unique('clients')->ignore($client->id)
			],
			'birth_date' => 'nullable|date',
			'gender' => 'nullable|string|in:M,F,O',
			'civil_status' => 'nullable|string|max:255',
			'workplace' => 'nullable|string|max:255',
			'occupation' => 'nullable|string|max:255',
			'independent' => 'nullable|boolean',
			'maximum_credit_allowed' => 'required|numeric'
		]);


		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}

		$client = Client::find($request->id);
		// $client->headquarter_id = $request['headquarter_id'];
		$client->name = $request['name'];
		$client->last_name = $request['last_name'];
		$client->type_document = $request['type_document'];
		$client->document = $request['document'];
		$client->profession = $request['profession'];
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
		$client->maximum_credit_allowed = $request['maximum_credit_allowed'];
		$client->photo = 'undefined';
		$client->update();

		$client->credits()->update(['headquarter_id' => $request['headquarter_id']]);

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'errors' =>  $validate->errors()
		], 200);
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
				->limit(10)
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

	public function validateCreditLimit($client_id, $amount) {
		$client = Client::find($client_id);
		$client->maximum_credit_allowed -= $amount;
		$client->update();
	}
}
