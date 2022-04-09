<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ProviderController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:provider.index', ['only' => ['index','show','filterProviderList']]);
		$this->middleware('permission:provider.store', ['only' => ['store']]);
		$this->middleware('permission:provider.update', ['only' => ['update']]);
		$this->middleware('permission:provider.delete', ['only' => ['create']]);
		$this->middleware('permission:provider.status', ['only' => ['changeStatus']]);
	}
	public function index()
	{
		//
		return Provider::paginate(5);
	}

	public function create($id)
	{
		//
		$provider = Provider::findOrFail($id);
		Provider::destroy($id);
		return redirect('provider')->with('mensaje', 'Provider eliminado correctamente');
	}

	public function store(Request $request)
	{
		$validate = Validator::make($request->all(), [
			'business_name' => 'required|string|max:100',
			'type_document' => 'required|in:CC,CE,NIT,PP,TI',
			'document' => 'required|numeric|min:999999|max:999999999999|unique:providers',
			'phone_1' => 'required|string',
			'phone_2' => 'nullable|string',
			'address' => 'nullable|string',
			'email' => 'required|string|email:rfc,dns|unique:providers',
		]);


		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}
		
		$provider = new Provider();
		$provider->business_name = $request['business_name'];
		$provider->type_document = $request['type_document'];
		$provider->document = $request['document'];
		$provider->phone_1 = $request['phone_1'];
		$provider->phone_2 = $request['phone_2'];
		$provider->address = $request['address'];
		$provider->email = $request['email'];
		$provider->save();

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'provider' =>  $provider
		], 200);
		
	}

	public function show(Provider $provider)
	{
		//
	}

	public function edit(Provider $provider)
	{
		//
	}

	public function update(Request $request, Provider $provider)
	{
		$validate = Validator::make($request->all(), [
			'business_name' => 'required|string|max:100',
			'type_document' => 'required|in:CC,CE,NIT,PP,TI',
			'document' => [
				'required',
				'numeric',
				'between:9999,999999999999',
				Rule::unique('providers')->ignore($provider->id)
			],
			'phone_1' => 'required|string',
			'phone_2' => 'nullable|string',
			'address' => 'nullable|string',
			'email' => [
				'required',
				'string',
				'email:rfc,dns',
				Rule::unique('providers')->ignore($provider->id)
			],
		]);


		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			], 400);
		}

		//$provider = Provider::find($request->id);
		$provider->business_name = $request['business_name'];
		$provider->type_document = $request['type_document'];
		$provider->document = $request['document'];
		$provider->phone_1 = $request['phone_1'];
		$provider->phone_2 = $request['phone_2'];
		$provider->address = $request['address'];
		$provider->email = $request['email'];
		$provider->update();

		return response()->json([
			'status' => 'success',
			'code' =>  200,
			'message' => 'Registro exitoso',
			'provider' =>  $provider
		], 200);
	}

	public function changeStatus(Provider $provider)
	{
		//
		$c = Provider::find($provider->id);
		$c->status = !$c->status;
		$c->save();
	}
	public function filterProviderList(Request $request)
	{
		if (!$request->provider || $request->provider == '') {
			$providers = Provider::select()
				->where('status', 1)
				->get();
		} else {
			$providers = Provider::select()
				->where('status', 1)
				->where('document', 'LIKE', "%$request->provider%")
				->orWhere('business_name', 'LIKE', "%$request->provider%")
				->get(20);
		}

		return $providers;
	}
}
