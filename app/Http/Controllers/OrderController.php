<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_variant_id' => ['required', 'exists:product_variants,id'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $customer = Customer::findOrFail($data['customer_id']);
        $discountPercent = match ($customer->tier) {
            'gold' => 10,
            'silver' => 5,
            default => 0,
        };

        $order = Order::create([
            'customer_id' => $customer->id,
            'status' => 'submitted',
            'subtotal_cents' => 0,
            'placed_at' => now(),
        ]);

        $subtotal = 0;

        foreach ($data['lines'] as $line) {
            $variant = ProductVariant::with('product')->find($line['product_variant_id']);
            $quantity = $line['quantity'];

            $variant->stock = $variant->stock - $quantity;
            $variant->save();

            $unitPrice = (int) round($variant->product->base_price_cents * (1 - $discountPercent / 100));

            $order->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
                'unit_price_cents' => $unitPrice,
            ]);

            $subtotal += $unitPrice * $quantity;
        }

        $order->update(['subtotal_cents' => $subtotal]);

        return response()->json([
            'data' => [
                'id' => $order->id,
                'status' => $order->status,
                'subtotal_cents' => $order->subtotal_cents,
                'placed_at' => $order->placed_at,
                'item_count' => $order->items()->count(),
            ],
        ], 201);
    }
}
