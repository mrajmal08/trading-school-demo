<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MarketData extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'api_id',
        'name',
        'original_price',
        'buffer_price',
        'price',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    public function marketDataStripe()
    {
        return $this->hasOne(MarketDataStripe::class);
    }

    public function marketDataPurchaseDetail()
    {
        return $this->hasMany(MarketDataPurchaseDetail::class);
    }

    public function challengeMarket()
    {
        return $this->hasMany(ChallengeMarket::class);
    }

}
