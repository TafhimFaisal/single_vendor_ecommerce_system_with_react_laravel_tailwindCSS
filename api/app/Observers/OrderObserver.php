<?php

namespace App\Observers;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderLog;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderPlaced;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $Admin = User::where('email','admin@admin.com')->first();
        $user  = $order->user;
        $carts = $user->carts->where('order_id','=',null)->all();

        foreach ($carts as $key => $cart) {
            $cart->order_id = $order->id;
            $cart->save();
        }

        $Admin->notify(new OrderPlaced($order,$user));

    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if($order->status == "delivered"){
            foreach ($order->carts as $key => $cart) {
                $this->update_product_qty($cart);
            }
        }

        $this->createLog($order,"update order");
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        // $this->createLog($order,"cancel order");
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }

    public function createLog($order,$message)
    {
        OrderLog::create([
            'cart' => json_encode($order->carts),
            'order'=> json_encode($order),
            'action'=> $message,
            'order_id' => $order->id,
            'user_id' => auth()->user()->id
        ]);
    }

    public function update_product_qty($cart)
    {
        $product = $cart->product;
        $product_qty = $cart->product->qty;
        $cart_qty = $cart->qty;

        $product->qty = $product_qty - $cart_qty;
        $product->save();
    }


}
