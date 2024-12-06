<?php

namespace App\Models\Products;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductWarehouse extends Model
{
    public $timestamps = false;

    protected $table = 'product_warehouse';

    protected $fillable = [
        'min_stock',
        'product_id',
        'warehouse_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
