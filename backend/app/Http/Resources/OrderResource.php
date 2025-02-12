<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'products' => collect($this->products)->map(function ($product) { 
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image_url' => getImageUrl($product),
                    'quantity' => $product->pivot->quantity,
                    'created_at' => $product->created_at->toDateTimeString(),
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
