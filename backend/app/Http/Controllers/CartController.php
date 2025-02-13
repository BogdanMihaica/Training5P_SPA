<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartProductsCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Returns a collection of products that are present in the session cart variable
     * 
     * @return CartProductsCollection
     */
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $products = Product::whereIn('id', $cartItems ? array_keys($cartItems) : [])->get();

        return new CartProductsCollection($products);
    }

    /**
     * Stores an item in the cart if it doesn't already exist
     * 
     * @param Product $product
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $id = $product->getKey();
        $cartItems = Session::get('cart',[]);
        $quantity = $validated['quantity'];
        
        Validator::make(['cart' => $cartItems], 
                        ['cart' => 
                        function ($attribute, $value, $fail) use ($cartItems, $id) {
                            if (array_key_exists($id, $cartItems)) {
                                $fail('Item already in the cart.');
                            }
                        }])->validate();
        
        $cartItems[$id] = $quantity;
        Session::put('cart', $cartItems);
    }

    /**
     * Removes an item from the session cart variable if it exists
     * 
     * @param Product $product
     */
    public function destroy(Product $product)
    {
        $id = $product->getKey();
        $cartItems = Session::get('cart');

        Validator::make(['cart' => $cartItems], 
                        ['cart' => 
                        function ($attribute, $value, $fail) use ($cartItems, $id) {
                            if (!Session::has('cart') || !array_key_exists($id, $cartItems)) {
                                $fail('Item not present in the cart.');
                            }
                        }])->validate();
        

        unset($cartItems[$id]);
        Session::put('cart', $cartItems);
    }
}
