<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    /**
     * Summary of products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Product, Order>
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot('quantity');
    }
}
