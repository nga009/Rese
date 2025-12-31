<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'integer',
        ];
    }

    /**
     * 店舗との関係
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * 予約との関係
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * 料金をフォーマット（円表示）
     */
    public function getFormattedPriceAttribute(): string
    {
        return '¥' . number_format($this->price);
    }
}
