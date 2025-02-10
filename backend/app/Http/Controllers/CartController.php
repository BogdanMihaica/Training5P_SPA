<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Returns a collection of products that are present in the session cart variable
     * 
     * @return CartProductCollection
     */
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $products = Product::whereIn('id', $cartItems ? array_keys($cartItems) : [])->get();

        return new CartProductCollection($products);
    }

    /**
     * Stores an item in the cart
     * 
     * @param Product $product
     * 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'];
        $id = $product->getKey();

        $cartItems = Session::get('cart',[]);

        if (array_key_exists($id, $cartItems)) {
            return response()->json(['message' => 'Item already in the cart.'], 500);
        }

        $cartItems[$id] = $quantity;

        Session::put('cart', $cartItems);

        return response()->json([], 200);
    }

    /**
     * Removes an item from the session cart variable if it exists and returns a status code
     * 
     * @param Product $product
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $id = $product->getKey();
        $cartItems = Session::get('cart');

        if (!Session::has('cart') || !array_key_exists($id, $cartItems)) {
            return response()->json(['message' => 'Item not present in the cart.'], 401);
        }

        unset($cartItems[$id]);
        Session::put('cart', $cartItems);

        return response()->json();
    }
}
