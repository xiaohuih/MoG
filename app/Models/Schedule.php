<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'begin_relativetime',
        'end_relativetime',
        'begin_date',
        'end_date',
        'begin_time',
        'duration',
        'interval',
        'wdays',
    ];
}
