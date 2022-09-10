<?php

namespace App\Http\Controllers;

use App\Models\CreditDebtor;
use Illuminate\Http\Request;

class CreditDebtorController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store($credit_id, $debtor_id)
    {
        $creditDebtor = new CreditDebtor();
        $creditDebtor->credit_id = $credit_id;
        $creditDebtor->debtor_id = $debtor_id;

        if ($creditDebtor->save()) {
            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Registro exitoso',
                'creditDebtor' => $creditDebtor
            ];
        } else {
            $data = [
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CreditDebtor  $creditDebtor
     * @return \Illuminate\Http\Response
     */
    public function show(CreditDebtor $creditDebtor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreditDebtor  $creditDebtor
     * @return \Illuminate\Http\Response
     */
    public function edit(CreditDebtor $creditDebtor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditDebtor  $creditDebtor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CreditDebtor $creditDebtor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreditDebtor  $creditDebtor
     * @return \Illuminate\Http\Response
     */
    public function destroy(CreditDebtor $creditDebtor)
    {
        //
    }
}
