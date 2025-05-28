<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Carbon\Carbon;

class WarehouseController extends Controller
{
    //
    public function report($id)
{
    $warehouse = Warehouse::with(['stocks.product'])->findOrFail($id);

    $report = $warehouse->stocks->map(function ($stock) {
        $is_near_expiry = Carbon::parse($stock->expiry_date)->diffInDays(now()) <= 7;

        return [
            'product' => $stock->product->name,
            'quantity' => $stock->quantity,
            'expiry_date' => $stock->expiry_date,
            'near_expiry' => $is_near_expiry,
        ];
    });

    return response()->json(['warehouse' => $warehouse->name, 'report' => $report]);
}

}
