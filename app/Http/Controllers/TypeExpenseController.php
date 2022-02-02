<?php

namespace App\Http\Controllers;

use App\Models\TypeExpense;
use Illuminate\Http\Request;

class TypeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $type_expenses = TypeExpense::select()->get();
        return $type_expenses;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type_expense =  new TypeExpense();
        $type_expense->description = $request['description'];
        $type_expense->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeExpense $type_expense)
    {
        $type_expense->description = $request['description'];
        $type_expense->save();
    }
}
