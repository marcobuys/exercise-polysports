<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->when($request->query('q'), function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            })
            ->when($request->query('category'), function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('name')
            ->get();

        $customer = Customer::find($request->query('customer_id'));
        ProductResource::$tier = $customer?->tier ?? 'bronze';

        return ProductResource::collection($products);
    }

    public function show(Request $request, Product $product): ProductResource
    {
        $customer = Customer::find($request->query('customer_id'));
        ProductResource::$tier = $customer?->tier ?? 'bronze';

        $product->load('variants');

        return new ProductResource($product);
    }
}
