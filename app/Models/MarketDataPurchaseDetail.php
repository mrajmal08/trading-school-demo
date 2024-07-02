<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MarketDataPurchaseDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'market_data_id',
        'user_id',
        'amp_id',
        'trader_id',
        'trader_name',
        'account_id',
        'account_name',
        'package_price',
        'account_activation_status',
        'stripe_amount',
        'log',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }

    public function packagePurchaseAccountDetail()
    {
        return $this->hasOne(PackagePurchaseAccountDetail::class, 'account_id', 'account_id');
    }

}
