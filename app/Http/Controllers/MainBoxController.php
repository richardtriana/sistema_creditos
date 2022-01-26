<?php

namespace App\Http\Controllers;

use App\Models\MainBox;
use Illuminate\Http\Request;

class MainBoxController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return response()->json([
			'status' => 'success',
			'code' => 200,
			'main_box' => MainBox::first()
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\Http\Requests\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function show(MainBox $mainBox)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function edit(MainBox $mainBox)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\Request  $request
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, MainBox $mainBox)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\MainBox  $mainBox
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(MainBox $mainBox)
	{
		//
	}
}
