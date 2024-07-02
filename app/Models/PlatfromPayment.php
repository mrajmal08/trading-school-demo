<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PlatfromPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'challenges_amount',
        'market_amount',
        'payment_getaway_amount',
        'other_amount',
        'total_amount',
        'system_cut_amount',
        'your_cut_amount',
        'status',
        'total_user',
        'total_purchase',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

}
