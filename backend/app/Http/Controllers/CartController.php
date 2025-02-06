<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Fetches to display the items that are in the cart
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $cartItems = session('cart');
        $products = Product::whereIn('id', $cartItems ? array_keys($cartItems) : [])->get();

        return view('cart', compact('products'));
    }

    /**
     * Stores an item in the session cart variable
     * 
     * @param integer $id
     * @param integer $quantity
     * 
     * @return mixed
     */
    public function store(Product $product)
    {
        request()->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = request()->input('quantity');
        $id = $product->getKey();

        $cartItems = session()->get('cart', []);

        if (isset($cartItems[$id])) {
            return redirect()->back();
        }

        $cartItems[$id] = $quantity;
        session()->put('cart', $cartItems);

        return redirect()->route('products.index');
    }

    /**
     * Removes an item from the session cart variable if it exists
     * 
     * @param integer $id
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $id = $product->getKey();
        $cartItems = session('cart');

        if (!session()->has('cart')) {
            abort(404);
        }

        if (!array_key_exists($id, $cartItems)) {
            abort(403);
        }

        unset($cartItems[$id]);
        session()->put('cart', $cartItems);

        return redirect()->route('cart.index');
    }
}
