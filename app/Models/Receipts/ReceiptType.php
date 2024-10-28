<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReceiptType extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}
