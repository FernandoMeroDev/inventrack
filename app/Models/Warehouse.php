<?php

namespace App\Models;

use App\Models\Receipts\Receipt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    public $timestamps = false;

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}
