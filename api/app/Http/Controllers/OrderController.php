<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Helper\CrudHelper;

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
            array_push($query,
                ['user_id','=',$this->user->id]
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
        $this->authorize('create',Order::class);
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
        $this->authorize('view',$order);
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
        $this->authorize('update',$order);
        $data = $request->all();

        if(!$this->user->is_admin){
            $data['user_id'] = $this->user->id;
            unset($data['status']);
        }

        if(isset($data['status'])){
            if($data['status'] == $order->status || $order->status == "delivered"){
                unset($data['status']);
            }
        }

        return $this->helper->update(
            $order,
            new OrderRequest($data)
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
        return  !$this->user->is_admin
                ? $this->cancel_order($order)
                : $this->helper->destroy($order);

    }

    public function cancel_order($order)
    {

        $this->authorize('update',$order);
        $order->canceled = true;
        $order->save();
        return response()->json([
            'message' => 'order canceled successfully.'
        ],200);

    }


    public function history(Order $order)
    {
        $this->authorize('isAdmin',$order);
        $data = $order->logs;
        return response()->json([
            'message' => 'order history fatched Successfully',
            'data' => $data,
            'type' => 'get order history'
        ],200);
    }

}
