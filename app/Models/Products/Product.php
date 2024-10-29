<?php

namespace App\Models\Products;

use App\Models\Receipts\Movement;
use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'min_stock'];

    public function salePrices(): HasMany
    {
        return $this->hasMany(SalePrice::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class)->withPivot('amount');
    }
}
