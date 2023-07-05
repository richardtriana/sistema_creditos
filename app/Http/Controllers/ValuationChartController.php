<?php

namespace App\Http\Controllers;

use App\Models\ValuationChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValuationChartController extends Controller
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
      'valuationChart' => ValuationChart::all()
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ValuationChart  $valuationChart
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // DB::enableQueryLog(); // Enable query log

    foreach ($request->data as $valuation) {

      $item = ValuationChart::find($valuation['id']);
      $item->valuation = $valuation['valuation'];
      $item->start = $valuation['start'];
      $item->end = $valuation['end'];
      $item->color = $valuation['color'];
      $item->update();
    }
    // DB::disableQueryLog(); // Enable query log


    return 'Datos actualizados correctamente';
  }
}
