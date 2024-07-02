<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TradingDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'account_id_number',
        'api_id',
        'is_active',
        'is_primary',
        'account_size',
        'account_name',
        'is_locked',
        'is_empty',
        'is_maybe_locked',
        'balance',
        'sodbalance',
        'current_daily_pl',
        'open_contracts',
        'daily_loss_limit',
        'net_liq_value',
        'current_drawdown',
        'drawdown_limit',
        'drawdown_type',
        'trading_day',
        'rule_one_enabled',
        'rule_one_value',
        'rule_one_key',
        'rule_one_maximum',
        'rule_two_enabled',
        'rule_two_value',
        'rule_two_key',
        'rule_two_maximum',
        'rule_three_enabled',
        'rule_three_value',
        'rule_three_key',
        'rule_three_maximum',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

}
