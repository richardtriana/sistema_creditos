<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Headquarter;
use Illuminate\Http\Request;

class HeadquarterController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$headquarters = Headquarter::select();
		if ($request->headquarter && ($request->headquarter != '')) {
			$headquarters  =     $headquarters->where('headquarter', 'LIKE', "%$request->headquarter%")
				->orWhere('nit', 'LIKE', "%$request->headquarter%")
				->orWhere('legal_representative', 'LIKE', "%$request->headquarter%")
				->orWhere('email', 'LIKE', "%$request->headquarter%");
		}
		$headquarters = $headquarters->paginate(5);

		return $headquarters;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id)
	{
		//
		$headquarter = Headquarter::findOrFail($id);
		Headquarter::destroy($id);
		return redirect('headquarter')->with('mensaje', 'Headquarter eliminado correctamente');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$headquarter = new Headquarter();
		$headquarter->headquarter = $request['headquarter'];
		$headquarter->status = $request['status'];
		$headquarter->address = $request['address'];
		$headquarter->nit = $request['nit'];
		$headquarter->email = $request['email'];
		$headquarter->legal_representative = $request['legal_representative'];
		$headquarter->pos_printer = $request['pos_printer'];
		$headquarter->phone = $request['phone'];
		$headquarter->save();

		$box = new Box();
		$box->headquarter_id = $headquarter->id;
		$box->save();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Headquarter  $headquarter
	 * @return \Illuminate\Http\Response
	 */
	public function show(Headquarter $headquarter)
	{
		abort(404);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Headquarter  $headquarter
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Headquarter $headquarter)
	{
		abort(404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Headquarter  $headquarter
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Headquarter $headquarter)
	{
		$headquarter = Headquarter::find($request->id);
		$headquarter->headquarter = $request['headquarter'];
		$headquarter->status = $request['status'];
		$headquarter->address = $request['address'];
		$headquarter->nit = $request['nit'];
		$headquarter->email = $request['email'];
		$headquarter->legal_representative = $request['legal_representative'];
		$headquarter->pos_printer = $request['pos_printer'];
		$headquarter->phone = $request['phone'];
		$headquarter->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Headquarter  $headquarter
	 * @return \Illuminate\Http\Response
	 */

	public function destroy($id)
	{
		abort(404);
	}

	public function changeStatus(Headquarter $headquarter)
	{
		$sd = Headquarter::find($headquarter->id);
		$sd->status = !$sd->status;
		$sd->save();
	}

	public function listHeadquarter()
	{
		$headquarters = Headquarter::select()->where('status', 1)->get();
		return $headquarters;
	}

	public function listAllHeadquarters()
	{
		$headquarters = Headquarter::select()->get();
		return $headquarters;
	}
}
