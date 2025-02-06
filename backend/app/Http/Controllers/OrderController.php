<?php

namespace App\Http\Controllers;

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
     * Returns the view responsible with displaying all the orders
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $orders = Order::query()->simplePaginate(12);

        return view('orders.orders', compact('orders'));
    }

    /**
     * Attempts to store an order in the orders table, then maps all the products in the cart to it's corresponding order id
     * in the order_products table. After all these sql operations, attempt to send an email to the admin with details about the order
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $cartItems = session('cart');

        if (!$cartItems) {
            return redirect()->back()->withErrors(['empty' => 'Your cart is empty']);
        }

        $validated = $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email']
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

        Notification::route('mail', config('mail.from')['address'])
            ->notify(new OrderSent($order->customer_email, $order->customer_name, $order));

        session()->forget('cart');

        return redirect()->route('products.index');
    }

    /**
     * Returns the view of an order with its corresponding products and details
     * 
     * @param int $id
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Order $order)
    {
        $products = $order->products;

        return view('orders.order', compact('products', 'order'));
    }
}
