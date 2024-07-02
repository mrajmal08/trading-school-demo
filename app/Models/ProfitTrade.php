<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProfitTrade extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'account_id_number',
        'total_pl',
        'number_trades',
        'number_contracts',
        'avg_trading_time',
        'longest_trading_time',
        'percent_profitable',
        'expectancy',
        'risk_management_id',
        'avgPlTrade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function riskManagement()
    {
        return $this->belongsTo(RiskManagement::class);
    }

}
