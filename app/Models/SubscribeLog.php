<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubscribeLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'stripe_id',
        'stripe_subscription_id',
        'payment_status',
        'response',
        'package_purchase_account_detail_id',
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
    public function packagePurchaseAccountDetail()
    {
        return $this->belongsTo(PackagePurchaseAccountDetail::class);
    }
}
