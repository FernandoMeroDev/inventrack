<?php

namespace App\Models\Shelves;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelProduct extends Model
{
    use HasFactory;

    protected $table = 'level_product';

    protected $fillable = ['amount', 'level_id', 'product_id'];

    public $timestamps = false;

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
