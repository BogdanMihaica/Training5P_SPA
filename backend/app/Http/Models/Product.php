<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Summary of orders
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Order, Product>
     */
    public function orders()
    {
        return $this
            ->belongsToMany(Order::class, 'order_products')
            ->withPivot('quantity');
    }
}
