<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class DeliveredOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart',
        'order',
        'user'
    ];

    /**
     * Get the order that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
