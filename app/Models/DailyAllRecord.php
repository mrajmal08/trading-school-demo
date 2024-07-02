<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DailyAllRecord extends Model
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
        'current_date',
        'total_pl',
        'number_trades',
        'number_contracts',
        'avg_trading_time',
        'longest_trading_time',
        'percent_profitable',
        'expectancy',
        'daily_record_id',
        'avgPlTrade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function dailyRecord()
    {
        return $this->belongsTo(DailyRecord::class);
    }
}
