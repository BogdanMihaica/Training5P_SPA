<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Fetches anddisplays all items from the products table that are not in the shopping cart
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {

        $cartItems = session('cart');
        $products = Product::whereNotIn('id', $cartItems ? array_keys($cartItems) : [])->simplePaginate(8);

        return view('welcome', compact('products'));
    }

    /**
     * Return the view for publishing a product
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('products.product', ['edit' => false]);
    }

    /**
     * Return the view for editing a product with a specified id

     * @param Product $product

     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        return view('products.product', ['edit' => true, 'product' => $product]);
    }

    /**
     * Queries and opens the view for the products dashboard along with the fetched products
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        $products = Product::query()->simplePaginate(12);

        return view('products.products', compact('products'));
    }

    /**
     * Attempts to destroy a product by its id
     * 
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        if ($product->image_filename) {
            Storage::disk('public')->delete('products' . DIRECTORY_SEPARATOR . $product->image_filename);
        }

        $product->delete();

        return redirect()->route('products.dashboard');
    }

    /**
     * Attempts to store a product in the database and saves the images associated with it if it exists
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $product = $this->validateAndSaveProduct(new Product());
        return redirect()->route('products.edit', $product);
    }

    /**
     * Attempts to update a product in the database and saves the images associated with it if it exists
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Product $product)
    {
        $this->validateAndSaveProduct($product);
        return redirect()->route('products.edit', $product);
    }

    /**
     * Validates and saves a product from the request
     * 
     * @param \App\Models\Product $product
     * 
     * @return Product
     */
    private function validateAndSaveProduct(Product $product)
    {
        $validated = request()->validate([
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'title' => ['required', 'max:50'],
            'description' => ['required', 'max:300'],
            'price' => ['required', 'numeric', 'min:1'],
        ]);

        $product->fill($validated);
        $product->save();

        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $fileName = $product->getKey() . '.' . $image->extension();

            if ($product->image_filename) {
                Storage::disk('public')->delete('products' . DIRECTORY_SEPARATOR . $product->image_filename);
            }

            $image->storeAs('products', $fileName, 'public');

            $product->image_filename = $fileName;
            $product->save();
        }

        return $product;
    }
}
