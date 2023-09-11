<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function  index()
		{
			$startDate = Carbon::now();
			$endDate = $startDate->clone()->addDay(2);

			//dd($startDate->toDateString(), $endDate->toDateString());

			$data = Client::where(function ($query) use ($startDate, $endDate){
					$query->whereMonth('birth_date', '>=',  $startDate->month)
						->whereMonth('birth_date', '<=', $endDate->month);
				})->where(function ($query) use ($startDate, $endDate){
						$query->whereDay('birth_date', '>=',  $startDate->day)
							->whereDay('birth_date', '<=', $endDate->day);
					})
				->get();

			return response()->json(
				[
					'status' => 'success',
					'code' => 200,
					'message' => 'Registro exitoso',
					'data' => $data
				],
				200
			);
		}
}
