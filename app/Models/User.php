<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'uuid',
        'email',
        'password',
        'otp',
        'account_id_number',
        'account_name',
        'device_token',
        'market_data_id',
        'ampid',
        'traderid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function historyReset()
    {
        return $this->hasMany(HistoryReset::class);
    }
    public function riskManagement()
    {
        return $this->hasMany(RiskManagement::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function subscribeLog()
    {
        return $this->hasMany(SubscribeLog::class);
    }

    public function allTrades()
    {
        return $this->hasMany(AllTrade::class);
    }
    public function profitTrades()
    {
        return $this->hasMany(ProfitTrade::class);
    }
    public function losingTrades()
    {
        return $this->hasMany(LosingTrade::class);
    }

    public function graphHistories()
    {
        return $this->hasMany(GraphHistory::class);
    }

    public function packagePurchaseAccountDetail()
    {
        return $this->hasMany(PackagePurchaseAccountDetail::class);
    }

    public function tradeRecord()
    {
        return $this->hasMany(TradeRecord::class);
    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }

    public function marketDataPurchaseDetail()
    {
        return $this->hasMany(MarketDataPurchaseDetail::class);
    }

    public function userMarketData()
    {
        return $this->hasMany(UserMarketData::class);
    }

    public function dailyRecord()
    {
        return $this->hasMany(DailyRecord::class);
    }
    public function dailyAllRecord()
    {
        return $this->hasMany(DailyAllRecord::class);
    }
    public function dailyLooseRecord()
    {
        return $this->hasMany(DailyLooseRecord::class);
    }
    public function dailyProfitRecord()
    {
        return $this->hasMany(DailyProfitRecord::class);
    }
}
