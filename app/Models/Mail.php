<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    // 全服邮件
    const TYPE_GLBOAL   = 1;
    // 普通邮件
    const TYPE_NORMAL   = 2;
}
