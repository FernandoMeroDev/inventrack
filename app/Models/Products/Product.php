<?php

namespace App\Models\Products;

use App\Models\Receipts\Movement;
use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'min_stock'];

    public function levelsIn(int $warehouseId): Collection
    {
        $levelProducts = LevelProduct::
                join('levels', 'levels.id', '=', 'level_product.level_id')
                ->join('shelves', 'levels.shelf_id', '=', 'shelves.id')
                ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
                ->select(
                    'level_product.id',
                    'shelves.name as shelf_name',
                    'levels.number',
                    'level_product.amount as product_amount'
                )->where('level_product.product_id', $this->id)
                ->where('warehouses.id', $warehouseId)
                ->get();
        return $levelProducts;
    }

    public function remainIn(int $warehouse_id): int
    {
        $lastMovement = static::join('movements', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->join('warehouses', 'receipts.warehouse_id', '=', 'warehouses.id')
            ->select('movements.id', 'movements.existences')
            ->where('movements.product_id', $this->id)
            ->where('receipts.warehouse_id', $warehouse_id)
            ->orderBy('id', 'desc')
            ->first();
        return $lastMovement?->existences ?? 0;
    }

    public function lastMovementIn(int $warehouse_id): ?Movement
    {
        return Movement::join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->select('movements.*')
            ->where('movements.product_id', $this->id)
            ->where('receipts.warehouse_id', $warehouse_id)
            ->orderBy('id', 'desc')
            ->first();
    }

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
