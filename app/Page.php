<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'page_title', 'description', 'content'];

    protected $guarded = [];
    protected $casts = [
        'active' => 'boolean'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getImageAttribute($img)
    {
        return (asset(Storage::url($img)));
    }

    public function getImageThumbAttribute($img)
    {
        return (asset(Storage::url($img)));
    }
}
