<?php

namespace App\Observers;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderLog;

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
        $user  = $order->user;
        $carts = $user->carts->where('order_id','=',null)->all();

        foreach ($carts as $key => $cart) {
            $cart->order_id = $order->id;
            $cart->save();
        }

    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function updated(Order $order)
    {
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
        $this->createLog($order,"cancel order");
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


}
