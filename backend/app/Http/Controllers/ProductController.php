<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Returns the collection of products that are not part of the cart
     * 
     * @return ProductCollection
     */
    public function index()
    {

        $cartItems = session('cart');
        $products = Product::whereNotIn('id', $cartItems ? array_keys($cartItems) : [])->get();

        return new ProductCollection($products);
    }

    /**
     * Get a specific product

     * @param Product $product

     * @return ProductResource
     */
    public function edit(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Returns a collection of all the products
     * 
     * @return ProductCollection
     */
    public function all()
    {
        return new ProductCollection(Product::all());
    }

    /**
     * Deletes a product from the database, as well as its corresponding image from the storage if there is one
     * 
     * @param Product $product
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        if ($product->image_filename) {
            Storage::disk('public')->delete('products' . DIRECTORY_SEPARATOR . $product->image_filename);
        }

        $product->delete();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Inserts a new product in the database and returns it as response
     * 
     * @return ProductResource
     */
    public function store()
    {
        $product = $this->validateAndSaveProduct(new Product());

        return new ProductResource($product);
    }

    /**
     * Updates a product from the database and returns it as response
     * 
     * @return ProductResource
     */
    public function update(Request $request, Product $product)
    {
        $this->validateAndSaveProduct($product);

        return new ProductResource($product);
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
