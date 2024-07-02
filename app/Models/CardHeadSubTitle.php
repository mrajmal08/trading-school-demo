<?php

namespace App\Models;

use App\Models\CardHead;
use App\Models\CardSubHeadTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CardHeadSubTitle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'card_head_id',
        'card_sub_head_title_id',
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function cardHead()
    {
        return $this->belongsTo(CardHead::class);
    }

    public function cardSubHeadTitle()
    {
        return $this->belongsTo(CardSubHeadTitle::class);
    }
}
