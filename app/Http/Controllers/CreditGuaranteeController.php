<?php

namespace App\Http\Controllers;

use App\Models\CreditGuarantee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class CreditGuaranteeController extends Controller
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
    public function store($credit_id, $guarantee_id)
    {
        // $creditGuarantee = new CreditGuarantee();
        // $creditGuarantee->credit_id = $credit_id;
        // $creditGuarantee->guarantee_id = $guarantee_id;
        // $creditGuarantee->date_add = date('Y-m-d');

        $creditGuarantee = CreditGuarantee::firstOrNew(
            ['credit_id' => $credit_id, 'guarantee_id' => $guarantee_id],
            ['date_add' =>  date('Y-m-d')]
        );


        if ($creditGuarantee->save()) {
            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Registro exitoso',
                'creditGuarantee' => $creditGuarantee
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
     * @param  \App\Models\CreditGuarantee  $creditGuarantee
     * @return \Illuminate\Http\Response
     */
    public function show(CreditGuarantee $creditGuarantee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreditGuarantee  $creditGuarantee
     * @return \Illuminate\Http\Response
     */
    public function edit(CreditGuarantee $creditGuarantee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditGuarantee  $creditGuarantee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CreditGuarantee $creditGuarantee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreditGuarantee  $creditGuarantee
     * @return \Illuminate\Http\Response
     */
    public function destroy(CreditGuarantee $creditGuarantee)
    {
        if ($creditGuarantee->delete()) {
            $data = [
                'status' => 'success',
                'code' => 200,
                'guarantee' => $creditGuarantee
            ];
        } else {
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Registro no encontrado'
            ];
        }
        return response()->json($data, $data['code']);
    }
}
