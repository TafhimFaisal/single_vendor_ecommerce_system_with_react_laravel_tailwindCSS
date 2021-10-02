<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    private $user;
    private $helper;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->helper = new CrudHelper(new Order,[],'Order');
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
            array_push($query,['user_id','=',$this->user->id]);
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
        $data = $request->all();
        if(!$this->user->is_admin){
            $data['user_id'] = $this->user->id;
            unset($data['status']);
        }

        return $this->helper->store(new OrderRequest($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if(!$this->user->is_admin && $order->user_id != $this->user->id){
            return response()->json([
                'message' => 'oops somthing went wrong !!!'
            ],401);
        }

        return $this->helper->get($order->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if(!$this->user->is_admin && $order->user_id != $this->user->id){
            return response()->json([
                'message' => 'oops somthing went wrong !!!'
            ],401);
        }

        return $this->helper->update(
            $order,
            new OrderRequest($request->all())
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if(!$this->user->is_admin){
            return response()->json([
                'message' => 'oops somthing went wrong !!!'
            ],401);
        }

        return $this->helper->destroy($order);
    }

    public function cancel_order(Order $order)
    {

        if(!$this->user->is_admin && $order->user_id != $this->user->id){
            return response()->json([
                'message' => 'oops somthing went wrong !!!'
            ],401);
        }

        $order->canceled = true;
        $order->save();

        return response()->json([
            'message' => 'order canceled successfully.'
        ],200);

    }

    public function get_product_under_order(Request $request,Order $order)
    {
        $query = [];

        $this->helper->changeModel(new Product);
        $this->helper->changetype('Product');

        if(!$this->user->is_admin){
            array_push($query,['user_id','=',$this->user->id]);
        }

        if($this->user->is_admin){
            array_push($query,['user_id','=',$request->user_id]);
        }

        array_push($query,['order_id','=',$order->id]);
        return $this->helper->get(null,$query);
    }
}
