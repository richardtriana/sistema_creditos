<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
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
      'company' => Company::first()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validate = Validator::make($request->all(), [
      'name' => 'required|string|min:3|max:100',
      'legal_representative' => 'required|string|min:3|max:150',
      'nit' => 'required|string|min:8|max:15',
      'address' => 'required|string|min:3|max:150',
      'email' => 'nullable|email',
      'telephone' => 'nullable|min:5|max:13',
      'mobile' => 'nullable|min:5|max:13',
      'file0' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
      'condition_order' => 'nullable|string',
      'condition_quotation' => 'nullable|string',
      'whatsapp_msg' => 'nullable|string',
      'method' => 'string',
    ]);

    if (!$validate->fails()) {
      if ($request->hasFile('file0')) {
        $image = uniqid() . $request->file('file0')->getClientOriginalName();
        $request->file0->move(public_path('storage/images'), $image);
        $request['logo'] = 'storage/images/' . $image;
      }

      $company = Company::updateOrCreate(['id' => $request->id], $request->all());

      $data = [
        'status' => 'success',
        'code' => 200,
        'message' => 'Registro exitoso',
        'company' => $company
      ];
    } else {
      $data = [
        'status' => 'error',
        'code' => 400,
        'message' => 'Validación de datos incorrecta',
        'errors' => $validate->errors()
      ];
    }

    return response()->json($data, $data['code']);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
