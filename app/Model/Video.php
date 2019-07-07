<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{

    protected $table = 'videos';

    protected $fillable = [
        'source_src',
        'name',
        'm3u8_src'
    ];
}
