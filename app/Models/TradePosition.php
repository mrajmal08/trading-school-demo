<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TradePosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'risk_management_id',
        'account_id',
        'index',
        'symbol',
        'quantity',
        'avgPrice',
        'realizedPl',
        'openPl',
        'totalPl',
        'priceScale',
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

    public function riskManagement()
    {
        return $this->belongsTo(RiskManagement::class);
    }
}
