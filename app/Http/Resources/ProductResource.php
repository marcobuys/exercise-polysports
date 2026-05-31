<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static string $tier = 'bronze';

    public function toArray(Request $request): array
    {
        $discountPercent = match (self::$tier) {
            'gold' => 10,
            'silver' => 5,
            default => 0,
        };

        $priceCents = (int) round($this->base_price_cents * (1 - $discountPercent / 100));

        $availability = [];
        $totalStock = 0;
        foreach ($this->variants as $variant) {
            $availability[] = [
                'variant_id' => $variant->id,
                'eu_size' => $variant->eu_size,
                'stock' => $variant->stock,
            ];
            $totalStock += $variant->stock;
        }

        $unitsSold = $this->orderItems->sum('quantity');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'category' => $this->category,
            'colorway' => $this->colorway,
            'base_price_cents' => $this->base_price_cents,
            'price_cents' => $priceCents,
            'tier' => self::$tier,
            'total_stock' => $totalStock,
            'units_sold' => $unitsSold,
            'availability' => $availability,
        ];
    }
}
