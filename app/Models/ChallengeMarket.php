<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ChallengeMarket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'card_challenge_id',
        'market_data_id',
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function cardChallenge()
    {
        return $this->belongsTo(CardChallenge::class);
    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }
}
