<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'course_id',
        'date',
        'time',
        'number',
        'payment_status',
        'stripe_payment_intent_id',
        'total_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'number' => 'integer',
        'total_amount' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * コースとの関係
     */
    public function course(): belongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 決済済みかどうか
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * 料金をフォーマット
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return '¥' . number_format($this->total_amount);
    }

    /**
     * QRコード用のトークン生成
     */
    public function getQrTokenAttribute(): string
    {
        return base64_encode($this->id . ':' . $this->created_at->timestamp);
    }

}
