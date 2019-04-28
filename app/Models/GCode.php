<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GCode extends Model
{
    // 无限制
    const TYPE_NOLIMIT  = 0;
    // 仅可领取一次
    const TYPE_ONCE     = 1;
    /**
     * 关联到模型的数据表
     ** @var string
     */
    protected $table = 'gcodes';
}
