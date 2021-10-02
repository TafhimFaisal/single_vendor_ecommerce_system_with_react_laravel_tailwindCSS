<?php

namespace App\Observers;
use App\Models\Order;
use App\Models\Cart;

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
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
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
}
