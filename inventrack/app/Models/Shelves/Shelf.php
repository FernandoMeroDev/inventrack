<?php

namespace App\Models\Shelves;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'warehouse_id'];

    public $timestamps = false;

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class);
    }
}
