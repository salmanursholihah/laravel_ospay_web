<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    use HasFactory;

    protected $guarded = [];
     protected $casts = [
            'base_price' => 'integer',
            'price_user' => 'integer',
            'price_agent' => 'integer',
            'raw_response' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'product_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Price Helpers
    |--------------------------------------------------------------------------
    */

    public function priceForRole(string $role): int
    {
        return match ($role) {
            'user' => $this->price_user,
            'agen' => $this->price_agent,
            default => throw new \InvalidArgumentException(
                "Role tidak memiliki harga produk: {$role}"
            ),
        };
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function commissionRules()
    {
        return $this->hasMany(CommissionRule::class, 'product_id');
}
}
