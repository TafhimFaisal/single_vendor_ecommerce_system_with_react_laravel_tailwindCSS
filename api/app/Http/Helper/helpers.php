<?php

    function move_delivared_order()
    {
        $delivared_orders = \App\Models\Order::where('status','delivered')->get();
        foreach ($delivared_orders as $key => $order) {
            \App\Models\DeliveredOrder::create([
                'cart'      => json_encode($order->carts),
                'order'     => json_encode($order),
                'user'      => json_encode($order->user)
            ]);
            $order->delete();
        }
    }

?>
