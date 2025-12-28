<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id'            => $this->id,
        'nama'   => $this->name,
        'slug'      => $this->slug,
        'jumlah_produk' => $this->whenCounted('products'),
        'link' => route('categories.show', $this->slug),
        ];
    }
}
