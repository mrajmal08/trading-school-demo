<?php

namespace App\Models;

use App\Models\cardHeadSubTitle;
use App\Models\CardHeadTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CardSubHeadTitle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'card_head_title_id',
        'value',
        'title',
    ];
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    public function cardHeadTitle()
    {
        return $this->belongsTo(CardHeadTitle::class);
    }

    public function cardHeadSubTitle()
    {
        return $this->hasMany(CardHeadSubTitle::class);
    }
}
