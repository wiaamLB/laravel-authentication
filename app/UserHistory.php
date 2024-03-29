<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHistory extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
