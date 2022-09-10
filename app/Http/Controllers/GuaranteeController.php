<?php

namespace App\Http\Controllers;

use App\Models\Guarantee;
use App\Http\Requests\StoreGuaranteeRequest;
use App\Http\Requests\UpdateGuaranteeRequest;
use Illuminate\Http\Request;

class GuaranteeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:guarantee.index', ['only' => ['index', 'show', 'filterGuaranteeList']]);
        $this->middleware('permission:guarantee.store', ['only' => ['store']]);
        $this->middleware('permission:guarantee.update', ['only' => ['update']]);
        $this->middleware('permission:guarantee.delete', ['only' => ['create']]);
        $this->middleware('permission:guarantee.status', ['only' => ['changeStatus']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guarantees = Guarantee::paginate(10);
        return $guarantees;
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
     * @param  \App\Http\Requests\StoreGuaranteeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGuaranteeRequest $request)
    {
        $guarantee =  new Guarantee();
        $guarantee->guarantee = $request['guarantee'];
        $guarantee->state = 1;
        $guarantee->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function show(Guarantee $guarantee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function edit(Guarantee $guarantee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGuaranteeRequest  $request
     * @param  \App\Models\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGuaranteeRequest $request, Guarantee $guarantee)
    {
        $guarantee->guarantee = $request->guarantee;
        $guarantee->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarantee $guarantee)
    {
        //
    }

    public function filterGuaranteeList(Request $request)
    {
        $guarantee = $request->query('guarantee');

        $guarantees = Guarantee::select()
            ->where('state', 1);

        if ($guarantee) {
            $guarantees = $guarantees
                ->where('guarantee', 'LIKE', "%$guarantee%");
        }
        
        return $guarantees->limit(10)->get();
    }

    public function changeStatus(Guarantee $guarantee)
    {
        $guarantee = Guarantee::find($guarantee->id);
        $guarantee->status = !$guarantee->status;
        $guarantee->save();
    }
}
