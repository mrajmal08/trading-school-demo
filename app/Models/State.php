<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class State extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id',
        'uuid',
        'country_id',
        'state_name',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->uuid = (string) Str::uuid();

        });

    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
