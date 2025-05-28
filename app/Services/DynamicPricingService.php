<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class DynamicPricingService
{
   public function updatePrice($productId)
    {
        // Add your dynamic pricing logic here
        $product = Product::find($productId);
        if ($product) {
            // Example logic: set price based on stock count
            $stock = $product->stocks()->sum('quantity');
            $basePrice = $product->base_price;

            // Example: reduce price if stock > 100
            $dynamicPrice = $stock > 100 ? $basePrice * 0.9 : $basePrice;

            $product->price = $dynamicPrice;
            $product->save();

            Log::info("Dynamic price updated for product {$product->id}: $dynamicPrice");
        }
    }

    
    public function calculate(Product $product)
    {
        $basePrice = $product->base_price;
        $stock = $product->stock;
        $note = null;

        if ($stock == 0) {
            $finalPrice = $basePrice * 1.20;
            $note = "Out of stock - 20% price increase applied.";
        } elseif ($stock <= 5) {
            $finalPrice = $basePrice * 1.15;
            $note = "Critically low stock - 15% price increase applied.";
        } elseif ($stock <= 10) {
            $finalPrice = $basePrice * 1.10;
            $note = "Low stock - 10% price increase applied.";
        } elseif ($stock <= 20) {
            $finalPrice = $basePrice * 1.05;
            $note = "Moderate stock - 5% price increase applied.";
        } else {
            $finalPrice = $basePrice;
            $note = "Sufficient stock - no price change.";
        }

        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'base_price' => number_format($basePrice, 2),
            'final_price' => number_format($finalPrice, 2),
            'stock' => $stock,
            'note' => $note,
        ];
    }
}
