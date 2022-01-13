<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Credit;
use App\Models\Installment;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $credits = Credit::select();
        $clients = Client::select();
        $headquarters = Headquarter::select();

        if ($request->credit && ($request->credit != '')) {
            $credits  =     $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
                ->where('document_number', 'LIKE', "%$request->credit%")
                ->orWhere('name', 'LIKE', "%$request->credit%")
                ->orWhere('email', 'LIKE', "%$request->credit%")
                ->orWhere('last_name', 'LIKE', "%$request->credit%")
                ->select('credits.*', 'credits.id as id', 'c.name', 'c.last_name', 'c.document_number');
        } else {
            $credits  =     $credits->leftjoin('clients as c', 'c.id', 'credits.client_id')
                ->where('document_number', 'LIKE', "%$request->credit%")
                ->orWhere('name', 'LIKE', "%$request->credit%")
                ->orWhere('email', 'LIKE', "%$request->credit%")
                ->orWhere('last_name', 'LIKE', "%$request->credit%")
                ->select('credits.*', 'credits.id as id', 'c.name', 'c.last_name', 'c.document_number');
        }

        $credits = $credits->paginate(10);

        return $credits;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $credit = Credit::findOrFail($id);
        Credit::destroy($id);
        return redirect('credit')->with('mensaje', 'Credit eliminado correctamente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $listInstallments = new InstallmentController();
        $listInstallments = $listInstallments->calcularInstallments($request);

        $credit = new Credit();
        $credit->client_id = $request['client_id'];
        $credit->debtor = $request['debtor'];
        $credit->debtor_id = $request['debtor_id'];
        $credit->headquarter_id = $request['headquarter_id'];
        $credit->number_installments = $request['number_installments'];
        $credit->number_paid_installments = $request['number_paid_installments'];
        $credit->day_limit = $request['day_limit'];
        $credit->status = $request['status'];
        $credit->start_date = date('Y-m-d');
        $credit->interest = $request['interest'];
        $credit->annual_interest_percentage = $request['annual_interest_percentage'];
        $credit->user_id = $request['user_id'];
        $credit->credit_value = $request['credit_value'];
        $credit->paid_value = $request['paid_value'];
        $credit->capital_value = $request['capital_value'];
        $credit->interest_value = $request['interest_value'];
        $credit->valor_cuota = $listInstallments['installment'];
        $credit->save();

        foreach ($listInstallments['listInstallments'] as $nueva_cuota) {
            $installment = new Installment();
            $installment->credit_id = $credit->id;
            $installment->nro_cuota = $nueva_cuota['cant_cuota'];
            $installment->value = $nueva_cuota['valor_cuota'];
            $installment->payment_date = $nueva_cuota['payment_date'];
            $installment->valor_pago_interes = $nueva_cuota['pagoInteres'];
            $installment->valor_pago_capital = $nueva_cuota['pagoCapital'];
            $installment->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show(Credit $credit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Credit $credit)
    {
        $credit = Credit::find($request->id);
        $credit->client_id = $request['client_id'];
        $credit->debtor_id = $request['debtor_id'];
        $credit->debtor = $request['debtor'];
        $credit->headquarter_id = $request['headquarter_id'];
        $credit->number_installments = $request['number_installments'];
        $credit->number_paid_installments = $request['number_paid_installments'];
        $credit->day_limit = $request['day_limit'];
        $credit->status = $request['status'];
        $credit->start_date = $request['start_date'];
        $credit->interest = $request['interest'];
        $credit->annual_interest_percentage = $request['annual_interest_percentage'];
        $credit->user_id = $request['user_id'];
        $credit->valor_cuota = $request['valor_cuota'];
        $credit->credit_value = $request['credit_value'];
        $credit->paid_value = $request['paid_value'];
        $credit->capital_value = $request['capital_value'];
        $credit->interest_value = $request['interest_value'];
        $credit->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Credit $credit)
    {
        //
        $cre = Credit::find($credit->id);
        $cre->status = !$cre->status;
        $cre->save();
    }

    public function installments(Request $request, $id)
    {

        $credit = Credit::findOrFail($id);
        return $credit->installments()->get();
    }


    public function payMultipleInstallments(Request $request, $id)
    {
        $credit_id = $id;
        $amount = $request['amount'];

        $credit = Credit::findOrFail($credit_id);
        $listInstallments = $credit->installments()->where('status', '0')->get();

        for ($i = 0; $i < count($listInstallments) && $amount > 0; $i++) {
            if ($amount > 0) {
                $installment = Installment::find($listInstallments[$i]['id']);
                if ($amount >= $listInstallments[$i]["value"]) {
                    $installment->paid_balance = $installment->value;
                    $installment->save();
                } else {
                    $installment->paid_balance = $amount;
                    $installment->status  = 1;
                    $installment->save();
                }
            }
            $amount = $amount - $listInstallments[$i]["value"];
        }
    }
}
