<?php

namespace App\Http\Controllers;

use App\Models\MainBox;
use Illuminate\Http\Request;
use App\Models\MainBoxHistory;

class MainBoxHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\MainBox  $mainBox
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MainBox $mainBox, string $message)
    {
        $user =  $request->user();
        $amount =  $request->amount;

        $mainBoxHistory = new MainBoxHistory();
        $mainBoxHistory->main_box_id       = $mainBox->id;
        $mainBoxHistory->user       = "$user->name $user->last_name";
        $mainBoxHistory->date       =  date('Y-m-d h:i:s A');
        $mainBoxHistory->value      = $amount;
        $mainBoxHistory->description = $message;
        $mainBoxHistory->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainBoxHistory  $mainBoxHistory
     * @return \Illuminate\Http\Response
     */
    public function show(MainBoxHistory $mainBoxHistory)
    {
        return $mainBoxHistory;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainBoxHistory  $mainBoxHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(MainBoxHistory $mainBoxHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainBoxHistory  $mainBoxHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MainBoxHistory $mainBoxHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainBoxHistory  $mainBoxHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainBoxHistory $mainBoxHistory)
    {
        //
    }
}
