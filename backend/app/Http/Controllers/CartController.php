<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Returns a collection of products that are present in the session cart variable
     * 
     * @return ProductCollection
     */
    public function index()
    {
        $cartItems = session('cart', []);
        $products = Product::whereIn('id', $cartItems ? array_keys($cartItems) : [])->get();

        return new ProductCollection($products);
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
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = $request->input('quantity');
        $id = $product->getKey();

        $cartItems = session()->get('cart', []);

        if (array_key_exists($id, $cartItems)) {
            return response()->json(['status' => 'failed', 'message' => 'Item already in the cart.'], 500);
        }

        $cartItems[$id] = $quantity;
        session()->put('cart', $cartItems);
        
        return response()->json(['status' => 'ok']);
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
        $cartItems = session('cart');

        if (!session()->has('cart') || !array_key_exists($id, $cartItems)) {
            return response()->json(['status' => 'failed','message' => 'Item not present in the cart.'],500);
        }

        unset($cartItems[$id]);
        session()->put('cart', $cartItems);

        return response()->json(['status' => 'ok']);
    }
}
