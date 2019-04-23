<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class GameRole extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'icon',
    ];
}
