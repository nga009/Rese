<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoriteShops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'favorites')->withTimestamps();
    }

    public function hasFavorited(Shop $shop): bool
    {
        return $this->favoriteShops()->where('shop_id', $shop->id)->exists();
    }

    public function toggleFavorite(Shop $shop): void
    {
        if ($this->hasFavorited($shop)) {
            $this->favoriteShops()->detach($shop->id);
        } else {
            $this->favoriteShops()->attach($shop->id);
        }
    }
    /**
     * 店舗との関係（店舗代表者の場合）
     */
    public function shops(): HasMany
    {
        return $this->HasMany(Shop::class);
    }
    /**
     * 管理者かどうか
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    /**
     * 店舗代表者かどうか
     */
    public function isShopOwner(): bool
    {
        return $this->role === 'shop';
    }
    /**
     * 一般ユーザーかどうか
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

}
