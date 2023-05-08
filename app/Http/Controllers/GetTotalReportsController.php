<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetTotalReportsController extends Controller
{

    public function getTotalReportPortfolio($results)
    {
        $data = [
            'interest_value' => $results->sum('interest_value'),
            'value' => $results->sum('value'),
            'capital_value' => $results->sum('capital_value')
        ];

        return $data;
    }

    public function getTotalReportHeadquartersExpenses($results){
        $data = [
            'price' => $results->sum('price')
        ];
        
        return $data;
    }

    public function getTotalReportHeadquartersEntries($results){
        $data = [
            'price' => $results->sum('price')
        ];
        
        return $data;
    }
}
