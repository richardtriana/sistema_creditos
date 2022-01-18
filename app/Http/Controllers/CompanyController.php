<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();
        return $company;
    }

    public function store(Request $request)
    {
        $company = new Company();
        $company->company = $request['company'];
        $company->status = $request['status'];
        $company->address = $request['address'];
        $company->nit = $request['nit'];
        $company->email = $request['email'];
        $company->legal_representative = $request['legal_representative'];
        $company->phone = $request['phone'];
        $company->save();
    }

    public function update(Request $request, Company $headquarter)
    {
        $company = Company::first();
        $company->company = $request['company'];
        $company->status = $request['status'];
        $company->address = $request['address'];
        $company->nit = $request['nit'];
        $company->email = $request['email'];
        $company->legal_representative = $request['legal_representative'];
        $company->phone = $request['phone'];
        $company->save();
    }
}
