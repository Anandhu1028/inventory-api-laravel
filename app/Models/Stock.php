<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //

    protected $fillable = ['product_id', 'warehouse_id', 'quantity', 'expiry_date'];
    public function product()
{
    return $this->belongsTo(Product::class);
}

public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

}
