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
        $type_expenses = TypeExpense::select()->paginate(20);
        return $type_expenses;
    }
}
