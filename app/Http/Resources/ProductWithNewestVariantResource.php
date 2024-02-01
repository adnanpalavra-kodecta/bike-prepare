<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithNewestVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'newest_variant' => [
                'id' => $this->newestVariant->id ?? null,
                'name' => $this->newestVariant->name ?? null,
                'price' => $this->newestVariant->price ?? null,
            ],
        ];
    }
}
