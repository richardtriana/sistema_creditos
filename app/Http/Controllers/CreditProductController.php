<?php

namespace App\Http\Controllers;

use App\Models\CreditProduct;
use Illuminate\Http\Request;

class CreditProductController extends Controller
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
    public function store($credit_id, $product_id)
    {
        $creditProduct = new CreditProduct();
        $creditProduct->credit_id = $credit_id;
        $creditProduct->product_id = $product_id;
        $creditProduct->date_add = date('Y-m-d');

        if ($creditProduct->save()) {
            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Registro exitoso',
                'creditProduct' => $creditProduct
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
     * @param  \App\Models\CreditProduct  $creditProduct
     * @return \Illuminate\Http\Response
     */
    public function show(CreditProduct $creditProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreditProduct  $creditProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(CreditProduct $creditProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditProduct  $creditProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CreditProduct $creditProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreditProduct  $creditProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CreditProduct $creditProduct)
    {
        //
    }
}
