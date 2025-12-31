<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'area_id',
        'genre_id',
        'description',
        'shop_image',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    /**
     * コースとの関係
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * 店舗代表者との関係
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 有効なコースのみ取得
     */
    public function activeCourses()
    {
        return $this->hasMany(Course::class)->where('is_active', true);
    }

    public function scopeAreaFilter($query, $areaId)
    {
        if ($areaId) {
            return $query->where('area_id', $areaId);
        }
        return $query;
    }

    public function scopeGenreFilter($query, $genreId)
    {
        if ($genreId) {
            return $query->where('genre_id', $genreId);
        }
        return $query;
    }

    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('name', 'like', "%{$keyword}%");
        }
        return $query;
    }
}
