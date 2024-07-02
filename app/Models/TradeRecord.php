<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TradeRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'trade_time',
        'account',
        'symbol',
        'buy',
        'sale',
        'quantity',
        'price',
        'risk_management_id',
        'scalePrice',
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

    public function packagePurchaseAccountDetail()
    {
        return $this->belongsTo(PackagePurchaseAccountDetail::class, 'account', 'account_id');

    }

    public function riskManagement()
    {
        return $this->belongsTo(RiskManagement::class);
    }

}
