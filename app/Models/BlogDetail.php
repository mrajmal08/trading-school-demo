<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogDetail extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

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
        'user_name',
        'title',
        'detail',
        'picture',
        'date',
        'publish',
        'tags',
        'user_image',
    ];

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
    }

}
