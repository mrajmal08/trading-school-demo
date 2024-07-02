<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MarketDataStripe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'market_data_id',
        'stripe_product_id',
        'stripe_product_price_id',
    ];
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }
}
