<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackagePurchaseMarket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'package_purchase_account_detail_id',
        'market_data_id',
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function packagePurchaseAccountDetail()
    {
        return $this->belongsTo(PackagePurchaseAccountDetail::class);
    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }
}
