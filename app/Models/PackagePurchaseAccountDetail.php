<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackagePurchaseAccountDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'request_id',
        'user_id',
        'amp_id',
        'trader_id',
        'trader_name',
        'account_id',
        'account_name',
        'new_customer_id',
        'card_challenge_id',
        'package_price',
        'account_status',
        'account_activation_status',
        'stripe_amount',
        'start_date',
        'end_date',
        'challenge_fail_popup',
        'rule_fail_popup',
        'new_account_status',
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

    public function userDetails()
    {
        return $this->belongsTo(UserDetail::class);
    }

    public function cardChallenge()
    {
        return $this->belongsTo(CardChallenge::class);
    }

    public function tradeRecord()
    {
        return $this->hasMany(TradeRecord::class, 'account', 'account_id');
    }

    public function subscribeLog()
    {
        return $this->hasOne(SubscribeLog::class);
    }

    public function historyReset()
    {
        return $this->hasMany(HistoryReset::class);
    }

    public function packagePurchaseMarket()
    {
        return $this->hasMany(PackagePurchaseMarket::class);
    }

}
