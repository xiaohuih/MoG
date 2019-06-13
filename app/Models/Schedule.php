<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * 关联到模型的数据表
     ** @var string
     */
    protected $table = 'game_schedules';
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
    
    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    // public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    // {
    // }

    // public function orderBy($column, $type)
    // {

    // }
}
