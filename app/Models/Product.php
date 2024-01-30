<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description',
        'product_type_id',
        'state',
        'validFrom',
        'validTo'
    ];
    public function varinats(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }
}
