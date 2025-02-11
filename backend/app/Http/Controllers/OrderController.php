<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderProductsCollection;
use App\Http\Resources\OrderResource;
use App\Mail\OrderPosted;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Notifications\OrderSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{

    /**
     * Returns all the orders from the database
     * 
     * @return OrderCollection
     */
    public function index()
    {
        return new OrderCollection(Order::all());
    }

    /**
     * Attempts to store an order in the orders table, then maps all the products in the cart to it's corresponding order id
     * in the order_products table. After all these sql operations, attempt to send an email to the admin with details about the order
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response|OrderResource
     */
    public function store(Request $request)
    {
        $cartItems = session('cart');

        $validated = $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email'],
            'cart' => function ($attribute, $value, $fail) use ($cartItems) {
                if (!$cartItems || count($cartItems) === 0) {
                    $fail('The cart is empty.');
                }
            }
        ]);

        $order = new Order();
        $order->customer_name = $validated['name'];
        $order->customer_email = $validated['email'];
        $order->save();

        foreach (array_keys($cartItems) as $itemId) {
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $itemId;
            $orderProduct->order_id = $order->getKey();
            $orderProduct->quantity = $cartItems[$itemId];
            $orderProduct->save();
        }

        // Notification::route('mail', config('mail.from')['address'])
        //     ->notify(new OrderSent($order->customer_email, $order->customer_name, $order));

        session()->forget('cart');

        return new OrderResource($order);
    }

    /**
     * Returns the details of a specified order
     * 
     * @param int $id
     * 
     * @return OrderResource
     */
    public function products(Order $order)
    {
        return new OrderResource($order);
    }
}
