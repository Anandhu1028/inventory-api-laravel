<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
      use HasFactory;
    //
    // Allow mass assignment
    protected $fillable = [
        'name',
        'sku',
        'description',
        'base_price',
        'stock',
    ];

    // Automatically append the dynamic price when converting to array/JSON
    protected $appends = ['dynamic_price'];

    /**
     * Relationship: One Product has many Stocks
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Accessor: Calculate dynamic price based on earliest expiring stock
     *
     * @return float
     */
    public function getDynamicPriceAttribute()
    {
        // Get the stock with the closest expiry date and available quantity
        $stock = $this->stocks()
            ->orderBy('expiry_date', 'asc')
            ->where('quantity', '>', 0)
            ->first();

        // No stock available, return base price
        if (!$stock) {
            return $this->base_price;
        }

        // Calculate days until expiry
        $daysToExpire = now()->diffInDays($stock->expiry_date, false);

        // Dynamic pricing rules
        if ($daysToExpire <= 10) {
            return round($this->base_price * 0.5, 2); // 50% off
        } elseif ($daysToExpire <= 30) {
            return round($this->base_price * 0.8, 2); // 20% off
        }

        // Otherwise, return base price
        return $this->base_price;
    }
    
}
