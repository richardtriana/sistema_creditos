<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\BoxHistory;
use Illuminate\Http\Request;

class BoxHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:box.index', ['only' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     * @param  \App\Models\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function index(Box $box)
    {
        $boxHistory = $box->boxHistory()->orderBy('id', 'desc')->get();
        return $boxHistory;
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Box $box, string $message)
    {
        $user =  $request->user();
        $amount =  ($request->add_amount) != 0 ? $request->add_amount : $request->current_balance;

        $boxHistory = new BoxHistory();
        $boxHistory->box_id       = $box->id;
        $boxHistory->user       = "$user->name $user->last_name";
        $boxHistory->date       =  date('Y-m-d h:i:s A');
        $boxHistory->value      = $amount;
        $boxHistory->description = $message;
        $boxHistory->cash       = $request->cash ?: NULL;
        $boxHistory->consignment_to_client = $request->consignment_to_client ?: NULL;
        $boxHistory->payment_to_provider = $request->payment_to_provider ?: NULL;
        $boxHistory->status     = $request->status ?: NULL;
        $boxHistory->observations = $request->observations ?: NULL;
        $boxHistory->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BoxHistory  $boxHistory
     * @return \Illuminate\Http\Response
     */
    public function show(BoxHistory $boxHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BoxHistory  $boxHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(BoxHistory $boxHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BoxHistory  $boxHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BoxHistory $boxHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BoxHistory  $boxHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(BoxHistory $boxHistory)
    {
        //
    }
}
