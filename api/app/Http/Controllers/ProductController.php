<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderLog;
use Illuminate\Http\Request;
use App\Http\Helper\CrudHelper;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    private $user;
    private $helper;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->helper = new CrudHelper(
            new Product,
            ['image'],
            'Product'
        );
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        move_delivared_order();
        return $this->helper->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',Product::class);
        $data = $request->all();
        $data['user_id'] = $this->user->id;
        return $this->helper->store(new ProductRequest($data));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->helper->get($product->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update',$product);
        $data = $request->all();
        $data['user_id'] = $this->user->id;

        return $this->helper->update(
            $product,
            new ProductRequest($data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete',$product);
        return $this->helper->destroy($product);
    }

    public function search(Request $request)
    {
        $query = [];
        array_push($query,
            ['name','LIKE',"%".$request->name."%"]
        );
        return $this->helper->get(null,$query);
    }

}
