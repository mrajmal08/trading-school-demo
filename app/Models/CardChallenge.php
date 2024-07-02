<?php

namespace App\Models;

use App\Models\CardHead;
use App\Models\ChallengeStripe;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CardChallenge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'service_id',
        'title',
        'buying_power',
        'price',
        'is_feature',
        'market_data_id',
        'clone_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function cardHead()
    {
        return $this->hasMany(CardHead::class);
    }

    public function challengeStripe()
    {
        return $this->hasOne(ChallengeStripe::class);
    }

    public function packagePurchaseAccountDetails()
    {
        return $this->hasMany(PackagePurchaseAccountDetail::class);
    }

    public function historyReset()
    {
        return $this->hasMany(HistoryReset::class);
    }

    public function riskManagement()
    {
        return $this->hasMany(RiskManagement::class);
    }

    public function challengeMarket()
    {
        return $this->hasMany(ChallengeMarket::class);
    }
}
