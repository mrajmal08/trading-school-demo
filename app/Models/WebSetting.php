<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WebSetting extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'web_settings';

    protected $fillable = [
        'uuid',
        'logo',
        'footer_logo_description',
        'term_of_service',
        'privacy_policy',
        'site_footer_copyright',
        'subscribe_title',
        'subscribe_description',
        'linkedin',
        'instagram',
        'facebook',
        'dark_logo',
        'privacy_policy_detail',
        'terms_service_detail',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }
}
