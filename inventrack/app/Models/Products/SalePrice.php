<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SalePrice extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['units_number', 'value', 'product_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function valueFormated(): string
    {
        return '$' . number_format($this->value, 2, ',', ' ');
    }
}
