<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'name'         => $this->name,
            'sku'          => $this->sku,
            'stock'        => $this->stock,
            'price'        => (float) $this->price,
            'status_stock' => $this->stock <= $this->min_stock ? 'Hampir Habis' : 'Tersedia',
            'created_at'   => $this->created_at->format('Y-m-d H:i:s'),
            ];
    }
}
