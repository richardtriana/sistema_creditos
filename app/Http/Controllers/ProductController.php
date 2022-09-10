<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.index', ['only' => ['index', 'show', 'filterProductList']]);
        $this->middleware('permission:product.store', ['only' => ['store']]);
        $this->middleware('permission:product.update', ['only' => ['update']]);
        $this->middleware('permission:product.delete', ['only' => ['create']]);
        $this->middleware('permission:product.status', ['only' => ['changeStatus']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(10);
        return $products;
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
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product =  new Product();
        $product->product = $request->product;
        $product->state = 1;
        $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->product = $request->product;
        $product->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function filterProductList(Request $request)
    {
        $product = $request->query('product');

        $products = Product::select()
            ->where('state', 1);

        if ($product) {
            $products = $products
                ->where('product', 'LIKE', "%$product%");
        }

        return $products->limit(10)->get();
    }

    public function changeStatus(Product $product)
    {
        //
        $product = Product::find($product->id);
        // $productlient->status = '0';
        $product->status = !$product->status;
        $product->save();
    }
}
