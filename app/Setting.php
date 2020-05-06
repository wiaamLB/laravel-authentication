<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use SoftDeletes;

    protected $fillable = ['social_media','contact_phone','contact_email'];

    protected $casts = [
        'social_media' => 'array',
        'others' => 'array'
    ];


}
