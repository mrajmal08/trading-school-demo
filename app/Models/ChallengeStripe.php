<?php

namespace App\Models;

use App\Models\CardChallenge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ChallengeStripe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'card_challenge_id',
        'stripe_product_id',
        'stripe_product_price_id',
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
}
