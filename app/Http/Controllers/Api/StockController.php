<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\DynamicPricingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{
    //
     public function store(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date|after:today',
        ]);

        // Use transaction to prevent race conditions
        $stock = DB::transaction(function () use ($data) {
            // Lock the stock row for update to avoid conflicts
            $stock = Stock::where('product_id', $data['product_id'])
                          ->where('warehouse_id', $data['warehouse_id'])
                          ->lockForUpdate()
                          ->first();

            if ($stock) {
                $stock->quantity += $data['quantity'];
                $stock->expiry_date = $data['expiry_date']; // or use latest expiry if needed
                $stock->save();
            } else {
                $stock = Stock::create($data);
            }

            // Update dynamic product pricing
            app(DynamicPricingService::class)->updatePrice($data['product_id']);

            return $stock;
        });

        return response()->json([
            'message' => 'Stock saved successfully',
            'stock' => $stock,
        ], 200);
    }
}
