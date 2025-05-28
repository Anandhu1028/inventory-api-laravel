<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Carbon;
use App\Services\DynamicPricingService;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with dynamic pricing and stock info.
     */
    public function index(DynamicPricingService $pricing): JsonResponse
    {
        $products = Product::with('stocks')->get()->map(function ($product) use ($pricing) {
            $totalStock = $product->stocks->sum('quantity');

            $nearExpiryStock = $product->stocks->filter(function ($stock) {
                return $stock->expiry_date && Carbon::parse($stock->expiry_date)->between(now(), now()->addDays(30));
            })->sum('quantity');

            return [
                'id' => $product->id,
                'name' => $product->name,
                'base_price' => $product->base_price,
                'dynamic_price' => $pricing->calculate($product),
                'total_stock' => $totalStock,
                'near_expiry_stock' => $nearExpiryStock,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Show pricing info for a specific product with optional warehouse context.
     */
    public function pricing(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $productId = $request->query('product_id');
        $warehouseId = $request->query('warehouse_id');

        $product = Product::find($productId);
        $basePrice = $product->base_price;

        $stockQuery = Stock::where('product_id', $productId);

        if ($warehouseId) {
            $stockQuery->where('warehouse_id', $warehouseId);
        }

        $totalStock = $stockQuery->sum('quantity');

        if ($totalStock <= 10) {
            $price = $basePrice * 1.10;
            $note = "Low stock - 10% price increase applied.";
        } else {
            $price = $basePrice;
            $note = "Normal stock - base price applied.";
        }

        return response()->json([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'base_price' => $basePrice,
            'final_price' => round($price, 2),
            'stock' => $totalStock,
            'note' => $note,
        ]);
    }

    /**
     * Get final price for a specific product based on total stock across warehouses.
     */
    public function getPrice($id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $totalStock = Stock::where('product_id', $product->id)->sum('quantity');
        $price = $product->base_price;

        if ($totalStock < 10) {
            $price *= 1.10; // 10% increase
        }

        return response()->json([
            'product_id' => $product->id,
            'price' => number_format($price, 2),
            'stock' => $totalStock
        ]);
    }
}
