<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HistoryReset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'package_purchase_account_detail_id',
        'amp_id',
        'trader_id',
        'trader_name',
        'account_id',
        'account_name',
        'card_challenge_id',
        'package_price',
        'account_status',
        'trading_day',
        'open_contracts',
        'current_daily_pl',
        'net_liq_value',
        'sodbalance',
        'rule_1_value',
        'rule_1_maximum',
        'rule_2_value',
        'rule_2_maximum',
        'rule_3_value',
        'rule_3_maximum',
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
    public function cardChallenge()
    {
        return $this->belongsTo(CardChallenge::class);
    }
    public function packagePurchaseAccountDetail()
    {
        return $this->belongsTo(PackagePurchaseAccountDetail::class);
    }
}
