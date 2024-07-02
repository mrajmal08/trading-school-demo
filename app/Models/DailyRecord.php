<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DailyRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'current_date',
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
        'dashboard_id',
        'isActive',
        'isPrimary',
        'accountSize',
        'isLocked',
        'isEmpty',
        'isMaybeLocked',
        'balance',
        'dailyLossLimit',
        'currentDrawdown',
        'drawdownLimit',
        'rule1Enabled',
        'rule1Key',
        'rule1Failed',
        'rule2Enabled',
        'rule2Key',
        'rule2Failed',
        'rule3Enabled',
        'rule3Key',
        'rule3Failed',
        'profitTarget',
        'minimumDays',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function dailyAllRecord()
    {
        return $this->hasOne(DailyAllRecord::class);
    }
    public function dailyLooseRecord()
    {
        return $this->hasOne(DailyLooseRecord::class);
    }
    public function dailyProfitRecord()
    {
        return $this->hasOne(DailyProfitRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
