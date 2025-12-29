<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'no_invoice'    => $this->reference_no,
            'tanggal'       => $this->created_at->format('d-m-Y H:i'),
            'catatan'       => $this->notes,
            'rincian_item'  => TransactionDetailResource::collection($this->whenLoaded('details')),
            'biaya_jasa'    => (float) $this->labor_cost,
            'total_bayar'   => (float) $this->total_price,
        ];
    }
}
