<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Http\Helper\CrudHelper;

class CartController extends Controller
{
    private $user;
    private $helper;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->helper = new CrudHelper(new Cart,[],'Cart');
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = [];
        if(!$this->user->is_admin){
            array_push($query,
                ['user_id','=',$this->user->id ],
                ['order_id','=',null]
            );
        }
        return $this->helper->get(null,$query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',Cart::class);
        $data = $request->all();
        return $this->helper->store(new CartRequest($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $this->authorize('view',$cart);
        return $this->helper->get($cart->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update',$cart);
        $data = $request->all();
        if(!$this->user->is_admin){
            $data['user_id'] = $this->user->id;
        }

        return $this->helper->update( $cart,new CartRequest($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $this->authorize('delete',$cart);
        return $this->helper->destroy($cart);
    }

    /**
     * creare resource in store.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function add_to_cart(Request $request, Product $product)
    {
        $data = $request->all();

        $data['product_id'] = $product->id;
        $data['price'] = $product->price;
        $data['product_name'] = $product->name;
        $data['user_id'] = $this->user->id;

        return $this->helper->store(new CartRequest($data));
    }


    /**
     * get all carts associated with order.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function carts_under_order($order)
    {
        $query = [];

        array_push($query,['order_id','=',$order]);
        if(!$this->user->is_admin){
            array_push($query,['user_id','=',$this->user->id ]);
        }

        return $this->helper->get(null,$query);
    }

}
