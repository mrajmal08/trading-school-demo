<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GraphHistory extends Model
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
        'day_index',
        'eod_net_liq',
        'eod_drawdown',
        'eod_profit_target',
        'risk_management_id',
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
