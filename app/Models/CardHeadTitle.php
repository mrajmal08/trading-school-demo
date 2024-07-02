<?php

namespace App\Models;

use App\Models\CardHead;
use App\Models\CardSubHeadTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CardHeadTitle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'title',
        'type',
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->type = Str::lower(Str::replace(' ', '', $model->title));
        });
    }

    public function cardSubHeadTitle()
    {
        return $this->hasMany(CardSubHeadTitle::class);
    }
    public function cardHead()
    {
        return $this->hasMany(CardHead::class);
    }
}
