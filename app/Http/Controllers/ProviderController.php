<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
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

		$provider = new Provider();
		$provider->name = $request['name'];
		$provider->last_name = $request['last_name'];
		$provider->type_document = $request['type_document'];
		$provider->document = $request['document'];
		$provider->phone_1 = $request['phone_1'];
		$provider->phone_2 = $request['phone_2'];
		$provider->address = $request['address'];
		$provider->email = $request['email'];
		$provider->save();
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
		$provider = Provider::find($request->id);
		$provider->name = $request['name'];
		$provider->last_name = $request['last_name'];
		$provider->type_document = $request['type_document'];
		$provider->document = $request['document'];
		$provider->phone_1 = $request['phone_1'];
		$provider->phone_2 = $request['phone_2'];
		$provider->address = $request['address'];
		$provider->email = $request['email'];
		$provider->save();
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
				->orWhere('name', 'LIKE', "%$request->provider%")
				->get(20);
		}

		return $providers;
	}
}
