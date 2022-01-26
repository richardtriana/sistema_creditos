<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$boxes = Box::select()->get();
		return response()->json([
			'status' => 'success',
			'code' => 200,
			'boxes' => $boxes
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
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function show(Box $box)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Box $box)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\Request  $request
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Box $box)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Box  $box
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Box $box)
	{
		//
	}
}
