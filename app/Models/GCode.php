<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class GCode extends Model
{
    protected static $cmd = 10002;
    /**
     * @var array
     */
    protected $fillable = [
        'status',
    ];
    /**
     * 类型
     */
    public static $types = [
        0 => 'nolimit',     // 无限制 
        1 => 'once',        // 仅可领取一次
    ];

    /**
     * 关联到模型的数据表
     ** @var string
     */
    protected $table = 'gcodes';
    /**
     * 列转换
     ** @var string
     */
    protected $casts = [
        'mail' => 'json',
    ];

    /**
     * 真实ID
     */
    public static function realId($id)
    {
        return $id + 1000;
    }
    
    /**
     * 发布
     */
    public function publish()
    {
        $cmd = 'PUBLISH_GCODE';
        $params = [
            'id' => self::realId((int)$this->id),
            'type' => (int)$this->type,
            'platform' => (int)$this->platform,
            'group' => (int)$this->group,
            'key' => $this->key,
            'mail' => $this->mail,
            'begintime' => isset($this->begintime) ? strtotime($this->begintime):0,
            'endtime' => isset($this->endtime) ? strtotime($this->endtime):0,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态
        if (true == $data['status']) {
            $this->update(['status' => 1]);
        }

        return $data;
    }

    /**
     * 取消发布
     */
    public function unpublish()
    {
        $cmd = 'UNPUBLISH_GCODE';
        $params = [
            'id' => self::realId((int)$this->id),
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态
        if (true == $data['status']) {
            $this->update(['status' => 0]);
        }

        return $data;
    }
}
