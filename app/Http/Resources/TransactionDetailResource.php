<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_produk'    => $this->product_id,
            'nama_produk' => $this->product->name,
            'sku'         => $this->product->sku,
            'jumlah'      => $this->quantity,
            'harga_satuan'=> (float) $this->price,
            'subtotal'    => (float) $this->subtotal,
        ];
    }
}
