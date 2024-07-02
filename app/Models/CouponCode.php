<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CouponCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'coupon_id',
        'coupon_code',
        'coupon_amount_policy',
        'amount',
        'date_range',
        'max_use',
        'currency',
        'status',
        'promotion_name',
        'total_number',
        'total_apply',
    ];

    protected static function boot()
    {
        parent::boot();

        // self::creating(function ($model) {

        //     $model->coupon_code = (string) substr(uniqid(), 0, 8);

        // });

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }
}
