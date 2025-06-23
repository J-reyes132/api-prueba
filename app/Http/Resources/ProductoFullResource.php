<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoFullResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'divisa' => [
                'id' => $this->divisa_id,
                'name' => $this->divisa->name ?? null, // Assuming divisa has a name attribute
                'symbol' => $this->divisa->symbol ?? null, // Assuming divisa has a symbol attribute
                'code' => $this->divisa->code ?? null, // Assuming divisa
                'exchange_rate' => $this->divisa->exchange_rate ?? null, // Assuming divisa has an exchange_rate attribute
            ],
            'tax_cost' => $this->tax_cost,
            'manufacturing_cost' => $this->manufacturing_cost,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
