<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Faqs extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'uuid',
        'description'
    ];

    public $hidden = ['id','created_at', 'updated_at', 'deleted_at'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });

    }
}
