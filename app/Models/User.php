<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'parent_agent_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
    ];

    /*
     * ================================================================
     * ROLE
     * ================================================================
     */

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agen';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /*
     * ================================================================
     * STATUS
     * ================================================================
     */

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /*
     * ================================================================
     * WALLET
     * ================================================================
     *
     * Hanya agen yang seharusnya mempunyai wallet.
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(
            Wallets::class,
            'user_id'
        );
    }

    /*
     * ================================================================
     * TRANSACTIONS
     * ================================================================
     */

    public function transactions(): HasMany
    {
        return $this->hasMany(
            Transactions::class
        );
    }

    /*
     * ================================================================
     * DEPOSITS
     * ================================================================
     */

    public function deposits(): HasMany
    {
        return $this->hasMany(
            Deposits::class
        );
    }

    /*
     * ================================================================
     * PARENT AGENT
     * ================================================================
     */

    public function parentAgent(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'parent_agent_id'
        );
    }

    /*
     * ================================================================
     * SUB USERS
     * ================================================================
     */

    public function subUsers(): HasMany
    {
        return $this->hasMany(
            User::class,
            'parent_agent_id'
        );
    }

    /*
     * ================================================================
     * COMMISSIONS
     * ================================================================
     */

    public function commissions(): HasMany
    {
        return $this->hasMany(
            Commissions::class,
            'agent_id'
        );
    }
}
