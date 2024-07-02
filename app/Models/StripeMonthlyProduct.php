<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripeMonthlyProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stripe_product_id',
        'stripe_monthly_product_id',
    ];

}
