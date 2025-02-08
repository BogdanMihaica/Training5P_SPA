<?php

use App\Models\Product;

/**
 * Get the public URL of a products image if the specified product object's image_filename variable is not null
 * 
 * @param mixed $product
 * 
 * @return string|null
 */
function getImageUrl($product)
{
    if (!$product->image_filename) {
        return;
    }

    $separator = DIRECTORY_SEPARATOR;

    return asset('storage' . $separator . 'products' . $separator . $product->image_filename);
}