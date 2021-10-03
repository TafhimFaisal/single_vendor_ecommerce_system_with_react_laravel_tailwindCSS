<?php

namespace App\Observers;
use App\Models\Cart;
use App\Models\OrderLog;

class CartObserver
{
    /**
     * Handle the cart "created" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function created(Cart $cart)
    {
        $this->createLog($cart,"creat cart");
    }

    /**
     * Handle the cart "updated" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function updated(Cart $cart)
    {
        $this->createLog($cart,"update cart");
    }

    /**
     * Handle the cart "deleted" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function deleted(Cart $cart)
    {
        $this->createLog($cart,"delete cart");
    }

    /**
     * Handle the cart "restored" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function restored(Cart $cart)
    {
        //
    }

    /**
     * Handle the cart "force deleted" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function forceDeleted(Cart $cart)
    {
        //
    }

    public function createLog($cart,$message)
    {
        OrderLog::create([
            'cart' => json_encode($cart),
            'order'=> json_encode($cart->order),
            'action'=> $message,
            'order_id' => $cart->order->id ?? null,
            'user_id' => auth()->user()->id
        ]);
    }
}
